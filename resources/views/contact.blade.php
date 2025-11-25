<x-app-layout>
  <div class="py-12 bg-gray-50">

    <div class="text-center mb-10">
      <h1 class="text-4xl font-bold text-gray-800">Hubungi Kami</h1>
      <p class="text-gray-500 text-lg mt-2">Butuh bantuan? Kirim pesan atau hubungi kami langsung.</p>
    </div>

    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 px-6">

      <!-- Left: Contact Form -->
      <div class="bg-white p-8 rounded-2xl shadow border">
        <h3 class="text-2xl font-bold mb-4 text-gray-800">Kirim Pesan</h3>

        <form class="space-y-4">
          <div>
            <label class="block text-gray-700 mb-1">Nama</label>
            <input type="text" class="w-full border rounded-lg p-3 focus:ring focus:ring-indigo-200">
          </div>

          <div>
            <label class="block text-gray-700 mb-1">Email</label>
            <input type="email" class="w-full border rounded-lg p-3 focus:ring focus:ring-indigo-200">
          </div>

          <div>
            <label class="block text-gray-700 mb-1">Pesan</label>
            <textarea class="w-full border rounded-lg p-3 h-32 focus:ring focus:ring-indigo-200"></textarea>
          </div>

          <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl">
            Kirim Pesan
          </button>
        </form>
      </div>

      <!-- Right: Contact Info -->
      <div class="bg-white p-8 rounded-2xl shadow border">

        <h3 class="text-2xl font-bold text-gray-800 mb-4">Informasi Kontak</h3>

        <p class="mb-4">
          <strong>📞 WhatsApp:</strong><br>
          <a href="https://wa.me/6285860067873" class="text-indigo-600 text-lg font-bold">
            0858-6006-7873
          </a>
        </p>

        <p class="mb-4">
          <strong>📧 Email:</strong><br>
          <span class="text-gray-700">karyaunyilmerch@gmail.com</span>
        </p>

        <p class="mb-4">
          <strong>⏰ Jam Operasional:</strong><br>
          Senin – Sabtu, 08.00 – 20.00
        </p>

        <p class="mb-4">
          <strong>📍 Alamat:</strong><br>
          Jl. Cibaduyut Lama No.25, Bandung
        </p>

        <!-- Social Media -->
        <div class="mt-6">
          <p class="font-semibold text-gray-700 mb-3">Ikuti Kami:</p>
          <div class="flex space-x-4 text-2xl">
            <a href="#" class="text-gray-600 hover:text-indigo-600">📘</a>
            <a href="#" class="text-gray-600 hover:text-indigo-600">📸</a>
            <a href="#" class="text-gray-600 hover:text-indigo-600">🎥</a>
          </div>
        </div>

        <!-- Small Map -->
        <div class="mt-8 rounded-xl overflow-hidden shadow border">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1983.0748917659893!2d107.5918826!3d-6.948496099999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e987c413933d%3A0xcf32773244550cbc!2sKarya%20Unyil%20Merchandiser!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid"
            width="100%" height="250" style="border:0;" allowfullscreen loading="lazy">
          </iframe>
        </div>

      </div>

    </div>

  </div>
</x-app-layout>
