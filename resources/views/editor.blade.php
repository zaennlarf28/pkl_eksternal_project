<x-app-layout>
  <div class="p-6 flex flex-col items-center space-y-6">

    <!-- TITLE -->
    <h1 class="text-3xl font-bold text-gray-800">
      🎨 {{ $design ? 'Edit Design' : 'New Design' }} – {{ $model?->name }}
    </h1>

    <!-- WRAPPER -->
    <div class="max-w-6xl w-full bg-white p-6 rounded-2xl shadow-xl border">

      <!-- TOOLBAR -->
      <div class="bg-gray-100 p-4 rounded-xl shadow-md space-y-3">

        <!-- ROW 1 -->
        <div class="flex flex-wrap justify-center gap-2">

          <button id="addText" class="btn-tool bg-blue-500 hover:bg-blue-600">Add Text</button>

          <label class="btn-tool bg-gray-700 hover:bg-gray-800 cursor-pointer">
            Upload Image
            <input type="file" id="uploadImage" class="hidden" accept="image/*">
          </label>

          <button id="duplicate" class="btn-tool bg-indigo-500 hover:bg-indigo-600">Duplicate</button>

          <button id="undo" class="btn-tool bg-yellow-500 hover:bg-yellow-600">Undo</button>
          <button id="redo" class="btn-tool bg-yellow-500 hover:bg-yellow-600">Redo</button>

          <button id="deleteObj" class="btn-tool bg-red-500 hover:bg-red-600">Delete</button>
          <button id="clearAll" class="btn-tool bg-red-700 hover:bg-red-800">Clear</button>

          <button id="saveDesign" class="btn-tool bg-green-500 hover:bg-green-600">Save (Ctrl+S)</button>
          <button id="exportPNG" class="btn-tool bg-purple-500 hover:bg-purple-600">Export PNG</button>
          <button id="sendToAdmin" class="btn-tool bg-blue-800 hover:bg-blue-900">Kirim ke Admin</button>

        </div>

        <!-- ROW 2 – ADVANCED CONTROLS -->
        <div class="flex flex-wrap justify-center gap-3 pt-2 items-center">

          <!-- Color Picker -->
          <input id="colorPicker" type="color" class="h-10 w-10 rounded cursor-pointer">

          <!-- Text Styling -->
          <button id="boldBtn" class="tool-small">Bold</button>
          <button id="italicBtn" class="tool-small">Italic</button>
          <button id="underlineBtn" class="tool-small">Underline</button>

          <!-- Flip & Opacity -->
          <button id="flipX" class="tool-small">Flip X</button>
          <button id="flipY" class="tool-small">Flip Y</button>

          <input id="opacitySlider" type="range" min="0.1" max="1" value="1" step="0.05">

          <!-- Center Object -->
          <button id="centerObj" class="tool-small">Center</button>

          <!-- Layering -->
          <button id="bringForward" class="tool-small">Up</button>
          <button id="sendBackward" class="tool-small">Down</button>

          <!-- Lock/Unlock -->
          <button id="lockBtn" class="tool-small">Lock</button>

          <!-- Print area & Grid & Select All -->
          <button id="togglePrintArea" class="tool-small">Toggle Print Area</button>
          <button id="toggleGrid" class="tool-small">Toggle Grid</button>
          <button id="selectAll" class="tool-small">Select All (Ctrl+A)</button>

        </div>
      </div>

      <!-- CANVAS -->
      <div class="flex justify-center mt-6">
        <canvas id="fabricCanvas" width="900" height="620" class="border rounded-lg shadow"></canvas>
      </div>
    </div>
  </div>


  <style>
    .btn-tool { @apply text-white px-3 py-2 rounded-lg text-sm font-medium shadow transition; }
    .tool-small { @apply bg-gray-200 px-3 py-1 rounded-md text-sm shadow hover:bg-gray-300; }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const canvas = new fabric.Canvas("fabricCanvas", {
        preserveObjectStacking: true,
        selection: true,
      });

      // State holders
      let bgImageObj = null;
      let undoStack = [];
      let redoStack = [];
      let isUndoRedo = false;
      let printArea = null;
      let guides = { v: null, h: null };
      let gridGroup = null;
      let gridVisible = false;
      let printAreaVisible = false;

      // helper: push state
      const pushState = () => {
  if (isUndoRedo) return; // ⚡ Jangan save state saat undo/redo
  redoStack = [];
  undoStack.push(JSON.stringify(canvas.toJSON()));
  if (undoStack.length > 60) undoStack.shift();
};

      // init from saved design if exists
      @if($design && $design->fabric_json)
        canvas.loadFromJSON({!! $design->fabric_json !!}, canvas.renderAll.bind(canvas), function(o, object) {});
      @else
        pushState();
      @endif

      // load background mockup (fit to canvas without cropping)
      @if($model && $model->image)
      fabric.Image.fromURL(`{{ asset('storage/'.$model->image) }}`, img => {
        const canvasW = canvas.width, canvasH = canvas.height;
        const scaleX = canvasW / img.width, scaleY = canvasH / img.height;
        const finalScale = Math.min(scaleX, scaleY) * 0.98;
        img.scale(finalScale);
        img.set({ left: (canvasW - img.width*finalScale)/2, top: (canvasH - img.height*finalScale)/2, selectable: false, evented: false });
        canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
        bgImageObj = img;
      });
      @endif

      // Save state on object add/modify/remove
      canvas.on("object:added", () => { if (!isUndoRedo) pushState(); });
canvas.on("object:modified", () => { if (!isUndoRedo) pushState(); });
canvas.on("object:removed", () => { if (!isUndoRedo) pushState(); });


      // ====== BASIC ACTIONS ======
      document.getElementById("addText").onclick = () => {
        const t = new fabric.IText("Tulis di sini", {
          left: 120, top: 120, fontSize: 36, fontFamily: "Poppins", fill: "#222"
        });
        canvas.add(t).setActiveObject(t);
      };

      document.getElementById("uploadImage").onchange = e => {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = () => {
          fabric.Image.fromURL(reader.result, img => {
            img.scaleToWidth(420);
            img.set({ left: (canvas.width - img.getScaledWidth())/2, top: (canvas.height - img.getScaledHeight())/2 });
            canvas.add(img).setActiveObject(img);
          });
        };
        reader.readAsDataURL(file);
      };

      document.getElementById("deleteObj").onclick = () => {
        const o = canvas.getActiveObject();
        if (o && !isHelper(o)) canvas.remove(o);
      };

      document.getElementById("clearAll").onclick = () => {
        if (!confirm("Hapus semua objek (kecuali mockup/background)?")) return;
        // Remove all selectable objects (keep background and helpers)
        canvas.getObjects().forEach(o => {
          if (!isHelper(o)) canvas.remove(o);
        });
        pushState();
      };

      // EXPORT
      document.getElementById("exportPNG").onclick = () => {
        // temporarily hide helpers
        hideHelpers();
        const link = document.createElement("a");
        link.href = canvas.toDataURL({ format: "png", quality: 1 });
        link.download = "design.png";
        link.click();
        showHelpers();
      };

      // SAVE
      document.getElementById("saveDesign").onclick = saveDesign;
      async function saveDesign() {
        const title = prompt("Nama desain:", "{{ $design->title ?? 'My Design' }}");
        if (!title) return;
        const res = await fetch("{{ route('design.save') }}", {
          method: "POST",
          headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
          body: JSON.stringify({
            id: {{ $design->id ?? 'null' }},
            title,
            model_id: {{ $model?->id ?? 'null' }},
            fabric_json: JSON.stringify(canvas.toJSON()),
            thumbnail: thumbnailData
          })
        });
        const data = await res.json();
        if (data.success) {
          alert("Desain tersimpan!");
          if (!{{ $design ? 'true' : 'false' }}) window.location.href = "/editor/" + data.id;
        } else alert("Gagal menyimpan.");
      }
// Ambil thumbnail dari canvas
const thumbnailData = canvas.toDataURL({
    format: 'png',
    quality: 0.8
});

document.getElementById("sendToAdmin").addEventListener("click", function () {

    let title = prompt("Judul desain (opsional):", "");
    if (title === null) return;

    const canvasEl = document.getElementById("fabricCanvas");

    canvasEl.toBlob(async function (blob) {

        const formData = new FormData();
        formData.append("image", blob, "design.png");
        formData.append("title", title || "");
        formData.append("model_id", "{{ $model->id ?? '' }}");
        formData.append("fabric_json", JSON.stringify(canvas.toJSON()));

        const res = await fetch("{{ route('designs.send_to_admin') }}", {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            }
        });

        let data;
        try {
            data = await res.json();
        } catch (e) {
            console.error("JSON error:", e);
            alert("SERVER ERROR 500 — Cek laravel.log");
            return;
        }

        if (res.ok && data.success) {
            alert("Berhasil dikirim ke admin!");
        } else {
            alert("Gagal mengirim desain.");
            console.error(data);
        }

    }, "image/png");

});


     // ========== FIXED UNDO ==========
document.getElementById("undo").onclick = () => {
  if (undoStack.length > 1) {
    isUndoRedo = true;

    const current = undoStack.pop();     // buang current
    redoStack.push(current);             // simpan untuk redo

    const prev = undoStack[undoStack.length - 1];

    canvas.loadFromJSON(prev, () => {
      canvas.renderAll();
      isUndoRedo = false;
    });
  }
};

// ========== FIXED REDO ==========
document.getElementById("redo").onclick = () => {
  if (redoStack.length > 0) {
    isUndoRedo = true;

    const json = redoStack.pop();
    undoStack.push(json);                // masuk lagi ke undo

    canvas.loadFromJSON(json, () => {
      canvas.renderAll();
      isUndoRedo = false;
    });
  }
};


      // DUPLICATE
      document.getElementById("duplicate").onclick = dupSelection;
      function dupSelection() {
        const active = canvas.getActiveObject();
        if (!active) return;
        active.clone(cloned => {
          cloned.set({ left: active.left + 20, top: active.top + 20 });
          canvas.add(cloned).setActiveObject(cloned);
        });
      }

      // SELECT ALL
      document.getElementById("selectAll").onclick = () => {
        const objs = canvas.getObjects().filter(o => !isHelper(o));
        if (!objs.length) return;
        const sel = new fabric.ActiveSelection(objs, { canvas });
        canvas.setActiveObject(sel);
        canvas.requestRenderAll();
      };

      // BRING / SEND
      document.getElementById("bringForward").onclick = () => {
        const o = canvas.getActiveObject(); if (o && !isHelper(o)) canvas.bringForward(o);
      };
      document.getElementById("sendBackward").onclick = () => {
        const o = canvas.getActiveObject(); if (o && !isHelper(o)) canvas.sendBackwards(o);
      };

      // LOCK / UNLOCK
      document.getElementById("lockBtn").onclick = () => {
        const o = canvas.getActiveObject();
        if (!o) return;
        const locked = o.lockMovementX || false;
        if (!locked) {
          o.set({ lockMovementX: true, lockMovementY: true, lockScalingX: true, lockScalingY: true, lockRotation: true });
          document.getElementById("lockBtn").innerText = "Unlock";
        } else {
          o.set({ lockMovementX: false, lockMovementY: false, lockScalingX: false, lockScalingY: false, lockRotation: false });
          document.getElementById("lockBtn").innerText = "Lock";
        }
        canvas.renderAll();
      };

      // FLIP
      document.getElementById("flipX").onclick = () => { const o = canvas.getActiveObject(); if (o && !isHelper(o)) { o.set("flipX", !o.flipX); canvas.renderAll(); } };
      document.getElementById("flipY").onclick = () => { const o = canvas.getActiveObject(); if (o && !isHelper(o)) { o.set("flipY", !o.flipY); canvas.renderAll(); } };

      // OPACITY SLIDER
      document.getElementById("opacitySlider").oninput = e => {
        const o = canvas.getActiveObject();
        if (!o || isHelper(o)) return;
        o.set("opacity", parseFloat(e.target.value));
        canvas.renderAll();
      };

      // COLOR PICKER
      document.getElementById("colorPicker").oninput = e => {
        const o = canvas.getActiveObject();
        if (!o || isHelper(o)) return;
        if (o.type && o.type.includes("text")) o.set("fill", e.target.value);
        else o.set("fill", e.target.value);
        canvas.renderAll();
      };

      // TEXT STYLE
      document.getElementById("boldBtn").onclick = () => toggleStyle("fontWeight", "bold", "normal");
      document.getElementById("italicBtn").onclick = () => toggleStyle("fontStyle", "italic", "normal");
      document.getElementById("underlineBtn").onclick = () => { const o = canvas.getActiveObject(); if (o?.type.includes("text")) { o.set("underline", !o.underline); canvas.renderAll(); } };

      function toggleStyle(prop, on, off) {
        const o = canvas.getActiveObject();
        if (!o || !o.type.includes("text")) return;
        o.set(prop, o[prop] === on ? off : on);
        canvas.renderAll();
      }

      // CENTER OBJECT
      document.getElementById("centerObj").onclick = () => {
        const o = canvas.getActiveObject();
        if (!o || isHelper(o)) return;
        o.center();
        o.setCoords();
        canvas.renderAll();
      };

      // AUTO-RESIZE TEXT on change (fit to width of selection/limit)
      canvas.on("object:modified", function(e) {
        const o = e.target;
        if (o && o.type.includes("text")) autoResizeText(o);
      });
      canvas.on("selection:created", e => { const o = e.target; if (o && o.type && o.type.includes("text")) autoResizeText(o); });

      function autoResizeText(textObj, maxWidth = canvas.width * 0.9) {
        if (!textObj || !textObj.type.includes("text")) return;
        let fontSize = textObj.fontSize || 40;
        textObj.set({ fontSize });
        canvas.renderAll();
        while (textObj.width > maxWidth && fontSize > 8) {
          fontSize -= 1;
          textObj.set({ fontSize });
          canvas.renderAll();
        }
      }

      // SNAP GUIDES
      const snapTolerance = 6;
      canvas.on("object:moving", function(e) {
        const moving = e.target;
        if (!moving || isHelper(moving)) return;

        // show vertical center guide
        const cx = moving.left + moving.getScaledWidth()/2;
        const cy = moving.top + moving.getScaledHeight()/2;

        const canvasCenterX = canvas.width/2;
        const canvasCenterY = canvas.height/2;

        // remove old guides
        removeGuides();

        // snap to canvas center
        if (Math.abs(cx - canvasCenterX) < snapTolerance) {
          showGuideVertical(canvasCenterX);
          moving.set({ left: moving.left + (canvasCenterX - cx) });
        }
        if (Math.abs(cy - canvasCenterY) < snapTolerance) {
          showGuideHorizontal(canvasCenterY);
          moving.set({ top: moving.top + (canvasCenterY - cy) });
        }

        // snap to other objects (simple: iterate objects and snap to their center or edges)
        canvas.getObjects().forEach(obj => {
          if (obj === moving || isHelper(obj)) return;
          const objCenterX = obj.left + obj.getScaledWidth()/2;
          const objCenterY = obj.top + obj.getScaledHeight()/2;

          // vertical align centers
          if (Math.abs(cx - objCenterX) < snapTolerance) {
            showGuideVertical(objCenterX);
            moving.set({ left: moving.left + (objCenterX - cx) });
          }
          // horizontal align centers
          if (Math.abs(cy - objCenterY) < snapTolerance) {
            showGuideHorizontal(objCenterY);
            moving.set({ top: moving.top + (objCenterY - cy) });
          }
        });

        canvas.requestRenderAll();
      });

      canvas.on("mouse:up", removeGuides);

      function showGuideVertical(x) {
        const line = new fabric.Line([x, 0, x, canvas.height], { stroke: "rgba(255,0,0,0.8)", strokeWidth: 1, selectable: false, evented: false, excludeFromExport: true });
        line.__isGuide = true;
        canvas.add(line);
      }
      function showGuideHorizontal(y) {
        const line = new fabric.Line([0, y, canvas.width, y], { stroke: "rgba(255,0,0,0.8)", strokeWidth: 1, selectable: false, evented: false, excludeFromExport: true });
        line.__isGuide = true;
        canvas.add(line);
      }
      function removeGuides() {
        canvas.getObjects().filter(o => o.__isGuide).forEach(o => canvas.remove(o));
      }
      function isHelper(o) { return o.__isGuide || o.__grid || o.__printArea; }

      // PRINT AREA OVERLAY
      document.getElementById("togglePrintArea").onclick = () => {
        if (!printArea) {
          // define safe area (example: centered rectangle with 90% width, 70% height)
          const w = canvas.width * 0.9, h = canvas.height * 0.7;
          const left = (canvas.width - w)/2, top = (canvas.height - h)/2;
          printArea = new fabric.Rect({
            left, top, width: w, height: h,
            fill: "rgba(0,0,0,0.06)", selectable: false, evented: false, stroke: "rgba(0,0,0,0.12)", strokeWidth: 1
          });
          printArea.__printArea = true;
          canvas.add(printArea);
          printAreaVisible = true;
        } else {
          printAreaVisible = !printAreaVisible;
          printArea.visible = printAreaVisible;
        }
        canvas.requestRenderAll();
      };

      // GRID (toggle)
      document.getElementById("toggleGrid").onclick = () => {
        gridVisible = !gridVisible;
        if (gridVisible) drawGrid(25);
        else removeGrid();
      };

      function drawGrid(step) {
        removeGrid();
        const group = [];
        for (let i = 0; i < canvas.width / step; i++) {
          const x = i * step;
          const line = new fabric.Line([x, 0, x, canvas.height], { stroke: "rgba(0,0,0,0.04)", selectable: false, evented: false });
          line.__grid = true; group.push(line);
        }
        for (let j = 0; j < canvas.height / step; j++) {
          const y = j * step;
          const line = new fabric.Line([0, y, canvas.width, y], { stroke: "rgba(0,0,0,0.04)", selectable: false, evented: false });
          line.__grid = true; group.push(line);
        }
        gridGroup = new fabric.Group(group, { selectable: false, evented: false });
        // add below all objects
        canvas.insertAt(gridGroup, 0);
        canvas.requestRenderAll();
      }

      function removeGrid() {
        if (!gridGroup) {
          canvas.getObjects().filter(o => o.__grid).forEach(o => canvas.remove(o));
        } else {
          try { canvas.remove(gridGroup); } catch(e) {}
          gridGroup = null;
        }
        canvas.requestRenderAll();
      }

      // BACKGROUND OPACITY CONTROL (via keyboard shortcut ALT+O or you'll add UI if needed)
      // Add a simple slider overlay for background control
      const bgOpacitySlider = document.createElement("input");
      bgOpacitySlider.type = "range";
      bgOpacitySlider.min = 0.1; bgOpacitySlider.max = 1; bgOpacitySlider.step = 0.05; bgOpacitySlider.value = 1;
      bgOpacitySlider.style.position = "fixed"; bgOpacitySlider.style.right = "18px"; bgOpacitySlider.style.bottom = "18px";
      bgOpacitySlider.title = "Background opacity";
      bgOpacitySlider.className = "hidden"; // hide by default; reveal if needed
      document.body.appendChild(bgOpacitySlider);
      bgOpacitySlider.oninput = () => {
        if (bgImageObj) { bgImageObj.set("opacity", parseFloat(bgOpacitySlider.value)); canvas.requestRenderAll(); }
      };


      // KEYBOARD SHORTCUTS
      document.addEventListener("keydown", (e) => {
        // Ctrl/Cmd + D = duplicate
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "d") { e.preventDefault(); dupSelection(); }
        // Ctrl/Cmd + A = select all
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "a") { e.preventDefault(); document.getElementById("selectAll").click(); }
        // Delete
        if (e.key === "Delete") { const o = canvas.getActiveObject(); if (o && !isHelper(o)) canvas.remove(o); }
        // Ctrl/Cmd + S = save
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "s") { e.preventDefault(); saveDesign(); }
        // Ctrl/Cmd + G = toggle grid
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "g") { e.preventDefault(); document.getElementById("toggleGrid").click(); }
        // ALT+O show bg opacity slider
        if (e.altKey && e.key.toLowerCase() === "o") { bgOpacitySlider.classList.toggle("hidden"); }
        // Ctrl/Cmd + J = toggle print area
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "j") { e.preventDefault(); document.getElementById("togglePrintArea").click(); }
      });

      // HELPERS: hide/show helpers for export
      function hideHelpers() {
        canvas.getObjects().forEach(o => { if (isHelper(o)) o.__prevVis = o.visible, o.visible = false; });
        canvas.requestRenderAll();
      }
      function showHelpers() {
        canvas.getObjects().forEach(o => { if (isHelper(o)) o.visible = (o.__prevVis !== undefined ? o.__prevVis : true); });
        canvas.requestRenderAll();
      }

      // UTILITY: remove guides on clear
      canvas.on("before:render", removeGuides);

    });
  </script>
</x-app-layout>
