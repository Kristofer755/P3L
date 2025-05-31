<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Bukti Pembayaran</title>
    <script src="https://unpkg.com/alpinejs@3.x.x" defer></script>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden w-full max-w-lg">
        <div 
            class="bg-white rounded-3xl shadow-xl overflow-hidden w-full max-w-lg"
            x-data="{
                preview: null,
                seconds: 60,
                startTimer() {
                this.interval = setInterval(() => {
                    if (this.seconds > 0) {
                    this.seconds--;
                    } else {
                    clearInterval(this.interval);
                    window.location.href = '{{ route('transaksi.batal', $transaksi->id_transaksi_pembelian) }}';
                    }
                }, 1000);
                }
            }"
            x-init="startTimer()"
        >
        <!-- Header -->
        <div class="bg-purple-600 p-6 flex items-center justify-between">
            <h1 class="text-white text-2xl font-bold">Upload Bukti Pembayaran</h1>
            <span class="text-purple-200 font-medium">No. {{ $transaksi->id_transaksi_pembelian }}</span>
        </div>
        <!-- Progress Bar -->
        <div class="px-6 py-4">
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div class="bg-purple-500 h-2.5 rounded-full" style="width: 100%"></div>
            </div>
            @if(session('alert'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('alert') }}</div>
            @endif
            <!-- Countdown Timer -->
            <div class="px-6 py-4 border-b">
            <p class="text-gray-700 mb-2 font-medium">Sisa waktu upload bukti:</p>
            <div class="text-xl font-bold text-purple-600">
                <span x-text="Math.floor(seconds / 60)"></span>:
                <span x-text="String(seconds % 60).padStart(2, '0')"></span>
            </div>
            </div>
            <form action="{{ route('transaksi.uploadBukti', $transaksi->id_transaksi_pembelian) }}" method="POST" enctype="multipart/form-data" x-data="{ preview: null }" @change="preview = $event.target.files.length ? URL.createObjectURL($event.target.files[0]) : null">
                <div class="px-6 py-4">
                    <form
                        action="{{ route('transaksi.uploadBukti', $transaksi->id_transaksi_pembelian) }}"
                        method="POST"
                        enctype="multipart/form-data"
                        @submit="clearInterval(interval)"   {{-- hentikan timer jika submit --}}
                        @change="preview = $event.target.files.length ? URL.createObjectURL($event.target.files[0]) : null"
                    >
                @csrf
                <!-- File Upload Card -->
                <div class="border-2 border-dashed border-purple-300 rounded-xl p-6 flex flex-col items-center justify-center cursor-pointer hover:border-purple-500 transition mb-6" @click="$refs.fileInput.click()">
                    <p class="text-purple-700 font-semibold">Seret dan jatuhkan atau klik untuk memilih file</p>
                    <p class="text-sm text-gray-500 mt-1">Format: JPG, JPEG, PNG. Maks 5MB</p>
                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" accept="image/*" class="hidden" x-ref="fileInput" @change="$dispatch('change')">
                </div>
                <!-- Preview -->
                <template x-if="preview">
                    <div class="mb-6 flex justify-center">
                        <img :src="preview" alt="Preview" class="rounded-lg shadow-md max-h-52">
                    </div>
                </template>
                @error('bukti_pembayaran')
                    <div class="text-red-600 text-sm mb-4">{{ $message }}</div>
                @enderror
                <!-- Submit -->
                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 rounded-lg shadow-lg transition">Upload Bukti</button>
            </form>
        </div>
        <!-- Footer -->
        <div class="bg-gray-50 p-4 text-center text-gray-500 text-sm">
            Silakan unggah bukti pembayaran untuk menyelesaikan transaksi Anda.
        </div>
    </div>
</body>
</html>