<x-app-layout>
  <div class="p-10 max-w-3xl mx-auto">
    <h1 class="text-3xl font-bold mb-4 text-center text-gray-800">Hubungi Kami</h1>
    <p class="text-gray-600 text-center mb-8">
      Ada pertanyaan, kerja sama, atau ingin pesan produk custom? Kami siap membantu Anda!
    </p>

    <div class="bg-white shadow rounded-xl p-6">
      <form action="#" method="POST" class="space-y-4">
        @csrf
        <div>
          <label class="block text-gray-700 font-medium">Nama</label>
          <input type="text" name="name" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" required>
        </div>

        <div>
          <label class="block text-gray-700 font-medium">Email</label>
          <input type="email" name="email" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" required>
        </div>

        <div>
          <label class="block text-gray-700 font-medium">Pesan</label>
          <textarea name="message" rows="4" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" required></textarea>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
          Kirim Pesan
        </button>
      </form>

      <div class="mt-8 text-center text-gray-500">
        📍 Jl. Kreatif No. 27, Yogyakarta<br>
        📞 0812-3456-7890<br>
        ✉️ hello@karyaunyil.com
      </div>
    </div>
  </div>
</x-app-layout>
