<x-app-layout>
  <div class="p-6 flex flex-col items-center">
    <h1 class="text-2xl mb-4 font-semibold">
      3D Preview - {{ ucfirst($model) }} ({{ $design->title }})
    </h1>

    <div id="threeContainer" class="w-full h-[600px] border border-gray-300 rounded-lg shadow"></div>

    <a href="{{ route('design.chooseModel', $design->id) }}" class="mt-4 text-blue-600 hover:underline">← Back to Models</a>
  </div>

  <script type="module">
    import * as THREE from 'https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js';
    import { OrbitControls } from 'https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/controls/OrbitControls.js';
    import { GLTFLoader } from 'https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/loaders/GLTFLoader.js';

    const container = document.getElementById('threeContainer');
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 0.1, 1000);
    camera.position.set(0, 1, 3);

    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(container.clientWidth, container.clientHeight);
    container.appendChild(renderer.domElement);

    const controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;

    const ambient = new THREE.AmbientLight(0xffffff, 1);
    scene.add(ambient);

    const directional = new THREE.DirectionalLight(0xffffff, 1);
    directional.position.set(5, 5, 5);
    scene.add(directional);

    const textureLoader = new THREE.TextureLoader();
    const texture = textureLoader.load('{{ asset("storage/" . $design->preview_path) }}');

    const loader = new GLTFLoader();
    loader.load('{{ asset($modelPath) }}', (gltf) => {
      const model = gltf.scene;
      model.traverse((child) => {
        if (child.isMesh) {
          child.material.map = texture;
          child.material.needsUpdate = true;
        }
      });
      scene.add(model);
      renderer.render(scene, camera);
    });

    function animate() {
      requestAnimationFrame(animate);
      controls.update();
      renderer.render(scene, camera);
    }
    animate();

    window.addEventListener('resize', () => {
      camera.aspect = container.clientWidth / container.clientHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(container.clientWidth, container.clientHeight);
    });
  </script>
</x-app-layout>
