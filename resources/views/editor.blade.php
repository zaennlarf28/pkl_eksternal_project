<x-app-layout>
  <div class="p-6 flex flex-col items-center space-y-6">
    <h1 class="text-2xl font-semibold text-gray-800">
      🎨 {{ $design ? 'Edit Design' : 'New Design' }}
    </h1>

    <!-- 🧭 Toolbar -->
    <div class="flex flex-wrap justify-center gap-2 bg-gray-100 p-3 rounded-lg shadow w-full max-w-6xl">
      <button id="addText" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Text</button>

      <label class="bg-gray-600 text-white px-4 py-2 rounded cursor-pointer hover:bg-gray-700">
        Upload Image
        <input type="file" id="uploadImage" class="hidden" accept="image/*">
      </label>

      <button id="undo" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Undo</button>
      <button id="redo" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Redo</button>

      <button id="bringFront" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">Front</button>
      <button id="sendBack" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">Back</button>

      <button id="lockObj" class="bg-slate-500 text-white px-4 py-2 rounded hover:bg-slate-600">Lock</button>
      <button id="deleteObj" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete</button>
      <button id="clearAll" class="bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800">Clear All</button>

      <button id="saveBtn" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Save</button>
      <button id="exportPNG" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">Export PNG</button>
      <button id="exportPDF" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">Export PDF</button>
      <button id="preview3D" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">Preview 3D</button>
    </div>

    <!-- 🎨 Canvas -->
    <div class="border border-gray-300 rounded-lg shadow bg-white flex justify-center items-center relative overflow-hidden w-[900px] h-[600px]">
      <canvas id="fabricCanvas" width="900" height="600"></canvas>
    </div>
  </div>

  <!-- 🧩 Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" defer></script>

  <script defer>
    window.addEventListener('load', () => {
      const canvas = new fabric.Canvas('fabricCanvas', { preserveObjectStacking: true });
      let undoStack = [], redoStack = [];

      // 🔄 Load old design
      @if($design && $design->fabric_json)
        canvas.loadFromJSON(@json($design->fabric_json), canvas.renderAll.bind(canvas));
      @endif

      // 🧩 Utility: Save state for undo
      const saveState = () => {
        redoStack = [];
        undoStack.push(JSON.stringify(canvas.toJSON()));
      };
      saveState();

      // 🔁 Undo/Redo
      document.getElementById('undo').onclick = () => {
        if (undoStack.length > 1) {
          redoStack.push(undoStack.pop());
          const prev = undoStack[undoStack.length - 1];
          canvas.loadFromJSON(prev, canvas.renderAll.bind(canvas));
        }
      };
      document.getElementById('redo').onclick = () => {
        if (redoStack.length > 0) {
          const next = redoStack.pop();
          undoStack.push(next);
          canvas.loadFromJSON(next, canvas.renderAll.bind(canvas));
        }
      };
      canvas.on('object:added', saveState);
      canvas.on('object:modified', saveState);
      canvas.on('object:removed', saveState);

      // ✏️ Add Text
      document.getElementById('addText').onclick = () => {
        const text = new fabric.IText('Double-click to edit', {
          left: 150, top: 150, fontSize: 36, fill: '#000', fontFamily: 'Poppins'
        });
        canvas.add(text).setActiveObject(text);
      };

      // 🖼 Upload Image
      document.getElementById('uploadImage').onchange = async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);

        try {
          const res = await fetch('{{ route("design.upload") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
          });

          const data = await res.json();
          console.log('Upload response:', data);

          if (data.url) {
            fabric.Image.fromURL(data.url, (img) => {
              img.set({ left: 200, top: 150, selectable: true });
              img.scaleToWidth(300);
              canvas.add(img).setActiveObject(img);
            });
          } else {
            alert('Upload gagal: ' + (data.error || 'Tidak diketahui'));
          }
        } catch (err) {
          console.error('Upload error:', err);
          alert('❌ Gagal upload gambar. Cek console untuk detail.');
        }
      };

      // 🧱 Layer Control
      document.getElementById('bringFront').onclick = () => {
        const obj = canvas.getActiveObject();
        if (obj) canvas.bringToFront(obj);
      };
      document.getElementById('sendBack').onclick = () => {
        const obj = canvas.getActiveObject();
        if (obj) canvas.sendToBack(obj);
      };

      // 🔒 Lock/Unlock
      document.getElementById('lockObj').onclick = () => {
        const obj = canvas.getActiveObject();
        if (!obj) return;
        const locked = !obj.lockMovementX;
        obj.lockMovementX = obj.lockMovementY = obj.lockRotation = obj.lockScalingX = obj.lockScalingY = locked;
        obj.hasControls = obj.hasBorders = !locked;
        obj.selectable = !locked;
        canvas.renderAll();
      };

      // 🗑 Delete & Clear
      document.getElementById('deleteObj').onclick = () => {
        const obj = canvas.getActiveObject(); 
        if (obj) canvas.remove(obj);
      };
      document.getElementById('clearAll').onclick = () => {
        if (confirm('Yakin hapus semua?')) canvas.clear();
      };

      // 💾 Save to DB (Fix JSON Error Handling)
      document.getElementById('saveBtn').onclick = async () => {
        const json = JSON.stringify(canvas.toJSON());
        const title = prompt('Title?', '{{ $design->title ?? 'Untitled' }}');
        if (!title) return;

        try {
          const res = await fetch('{{ route("design.save") }}', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: '{{ $design->id ?? "" }}', title, fabric_json: json }),
          });

          const text = await res.text();
          console.log('Server response:', text);

          const data = JSON.parse(text);
          if (data.success) {
            alert('✅ Saved: ' + data.title);
          } else {
            alert('❌ Save failed: ' + (data.error || 'Unknown error'));
          }
        } catch (err) {
          console.error('Save error:', err);
          alert('❌ Gagal menyimpan. Cek console untuk detail error.');
        }
      };

      // 📤 Export PNG
      document.getElementById('exportPNG').onclick = () => {
        const dataURL = canvas.toDataURL({ format: 'png', quality: 1 });
        const link = document.createElement('a');
        link.href = dataURL; link.download = 'design.png'; link.click();
      };

      // 📄 Export PDF
      document.getElementById('exportPDF').onclick = () => {
        const { jsPDF } = window.jspdf;
        const dataURL = canvas.toDataURL({ format: 'png', quality: 1 });
        const pdf = new jsPDF('l', 'pt', [canvas.width, canvas.height]);
        pdf.addImage(dataURL, 'PNG', 0, 0, canvas.width, canvas.height);
        pdf.save('design.pdf');
      };

      // 🧊 Preview 3D
     document.getElementById('preview3D').onclick = async () => {
  const json = JSON.stringify(canvas.toJSON());
  const title = prompt('Masukkan judul desain:', '{{ $design->title ?? 'Untitled' }}');
  if (!title) return;

  try {
    const res = await fetch('{{ route("design.save") }}', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ id: '{{ $design->id ?? "" }}', title, fabric_json: json }),
    });

    const text = await res.text();
    console.log('Preview3D response:', text);

    const data = JSON.parse(text);
    if (data.success && data.id) {
      // arahkan ke halaman pilih model dulu
      window.location.href = `/design/${data.id}/choose-model`;
    } else {
      alert('❌ Gagal menyimpan atau membuka 3D preview.');
    }
  } catch (err) {
    console.error('Preview3D error:', err);
    alert('❌ Terjadi error saat membuka preview 3D.');
  }
};


      // 🖱 Zoom & Pan
      let zoom = 1;
      canvas.on('mouse:wheel', (opt) => {
        const delta = opt.e.deltaY;
        zoom *= 0.999 ** delta;
        zoom = Math.min(Math.max(zoom, 0.5), 3);
        canvas.setZoom(zoom);
        opt.e.preventDefault();
        opt.e.stopPropagation();
      });
    });
  </script>
</x-app-layout>
