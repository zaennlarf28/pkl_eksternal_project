<x-app-layout>
  <div class="p-6 flex flex-col items-center space-y-6">
    <h1 class="text-2xl font-semibold text-gray-800">
      🎨 {{ $design ? 'Edit Design' : 'New Design' }} - {{ $model?->name }}
    </h1>

    <div class="max-w-5xl w-full bg-white p-6 rounded-2xl shadow border">
      <!-- Toolbar -->
      <div class="flex flex-wrap justify-center gap-2 bg-gray-50 p-3 rounded-lg shadow w-full mb-4">
        <button id="addText" class="btn-tool bg-blue-500 hover:bg-blue-600">Add Text</button>
        <label class="btn-tool bg-gray-600 hover:bg-gray-700 cursor-pointer">
          Upload Image
          <input type="file" id="uploadImage" class="hidden" accept="image/*">
        </label>
        <button id="undo" class="btn-tool bg-yellow-500 hover:bg-yellow-600">Undo</button>
        <button id="redo" class="btn-tool bg-yellow-500 hover:bg-yellow-600">Redo</button>
        <button id="deleteObj" class="btn-tool bg-red-500 hover:bg-red-600">Delete</button>
        <button id="clearAll" class="btn-tool bg-red-700 hover:bg-red-800">Clear</button>
        <button id="exportPNG" class="btn-tool bg-purple-500 hover:bg-purple-600">Export PNG</button>
      </div>

      <!-- Canvas -->
      <div class="flex justify-center">
        <canvas id="fabricCanvas" width="700" height="500" class="border rounded-lg shadow"></canvas>
      </div>
    </div>
  </div>

  <style>
    .btn-tool { @apply text-white px-3 py-2 rounded-lg text-sm font-medium shadow transition-all; }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const canvas = new fabric.Canvas('fabricCanvas', { preserveObjectStacking: true });

      // 🔹 Tambahkan background model kalau ada
      @if($model && $model->image)
        fabric.Image.fromURL('{{ asset('storage/'.$model->image) }}', (img) => {
          img.scaleToWidth(canvas.width);
          canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
        });
      @endif

      // Undo/Redo stack
      let undoStack = [], redoStack = [];
      const saveState = () => { redoStack = []; undoStack.push(JSON.stringify(canvas.toJSON())); };
      const loadState = (json) => canvas.loadFromJSON(json, canvas.renderAll.bind(canvas));

      saveState();
      canvas.on('object:added', saveState);
      canvas.on('object:modified', saveState);

      document.getElementById('undo').onclick = () => {
        if (undoStack.length > 1) {
          redoStack.push(undoStack.pop());
          loadState(undoStack[undoStack.length - 1]);
        }
      };
      document.getElementById('redo').onclick = () => {
        if (redoStack.length) {
          const state = redoStack.pop();
          undoStack.push(state);
          loadState(state);
        }
      };

      // Add Text
      document.getElementById('addText').onclick = () => {
        const text = new fabric.IText('Tulis di sini...', {
          left: 100, top: 100, fontSize: 32, fill: '#222'
        });
        canvas.add(text).setActiveObject(text);
      };

      // Upload Image
      document.getElementById('uploadImage').onchange = (e) => {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = () => {
          fabric.Image.fromURL(reader.result, (img) => {
            img.scaleToWidth(250);
            img.set({ left: 200, top: 150 });
            canvas.add(img).setActiveObject(img);
          });
        };
        reader.readAsDataURL(file);
      };

      // Delete & Clear
      document.getElementById('deleteObj').onclick = () => {
        const obj = canvas.getActiveObject();
        if (obj) canvas.remove(obj);
      };
      document.getElementById('clearAll').onclick = () => {
        if (confirm('Hapus semua desain?')) canvas.clear();
      };

      // Export PNG
      document.getElementById('exportPNG').onclick = () => {
        const link = document.createElement('a');
        link.href = canvas.toDataURL({ format: 'png', quality: 1 });
        link.download = 'design.png';
        link.click();
      };
    });
  </script>
</x-app-layout>
