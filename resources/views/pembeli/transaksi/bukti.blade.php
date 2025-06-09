<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Bukti Pembayaran</title>
    <script src="https://unpkg.com/alpinejs@3.x.x" defer"></script>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #4b0082 0%, #7b2d8e 50%, #9d4edd 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .upload-zone {
            background: linear-gradient(45deg, rgba(75, 0, 130, 0.05), rgba(157, 78, 221, 0.05));
            border: 2px dashed #4b0082;
            transition: all 0.3s ease;
        }
        .upload-zone:hover {
            background: linear-gradient(45deg, rgba(75, 0, 130, 0.1), rgba(157, 78, 221, 0.1));
            border-color: #7b2d8e;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(75, 0, 130, 0.2);
        }
        .btn-primary {
            box-shadow: 0 4px 15px rgba(75, 0, 130, 0.3);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(75, 0, 130, 0.4);
        }
        .timer-glow {
            text-shadow: 0 0 10px rgba(75, 0, 130, 0.5);
        }
        .progress-bar {
            background: linear-gradient(90deg, #4b0082, #7b2d8e);
            box-shadow: 0 2px 10px rgba(75, 0, 130, 0.3);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4" >
    <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden w-full max-w-lg pulse-animation">
        <div 
            class="w-full max-w-lg"
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
        <!-- Header dengan efek gradient -->
        <div class=" p-6 flex items-center justify-between relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent"></div>
            <h1 class="text-white text-2xl font-bold relative z-10 drop-shadow-lg">Upload Bukti Pembayaran</h1>
            <span class="text-purple-100 font-medium relative z-10 bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">No. {{ $transaksi->id_transaksi_pembelian }}</span>
        </div>

        <!-- Progress Bar dengan glow effect -->
        <div class="px-6 py-4">
            <div class="w-full bg-gray-200 rounded-full h-3 mb-4 shadow-inner">
                <div class="progress-bar h-3 rounded-full" style="width: 100%"></div>
            </div>
            @if(session('alert'))
                <div class="mb-4 p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 shadow-sm">
                    <div class="flex items-center">
                        
                        {{ session('alert') }}
                    </div>
                </div>
            @endif

            <!-- Enhanced Countdown Timer -->
            <div class="px-6 py-4 border-b border-purple-100 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl mb-4">
                <p class="text-gray-700 mb-2 font-medium flex items-center">
                    
                    Sisa waktu upload bukti:
                </p>
                <div class="text-3xl font-bold timer-glow" style="color: #4b0082;">
                    <span x-text="Math.floor(seconds / 60)"></span>:
                    <span x-text="String(seconds % 60).padStart(2, '0')"></span>
                </div>
            </div>

            <form
                action="{{ route('transaksi.uploadBukti', $transaksi->id_transaksi_pembelian) }}"
                method="POST"
                enctype="multipart/form-data"
                x-data="{ preview: null }"
                @submit="clearInterval(interval)"
                @change="preview = $event.target.files.length ? URL.createObjectURL($event.target.files[0]) : null"
            >
                @csrf
                <!-- Enhanced File Upload Zone -->
                <div class="upload-zone rounded-2xl p-8 flex flex-col items-center justify-center cursor-pointer mb-6 relative overflow-hidden" @click="$refs.fileInput.click()">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-100/50 to-indigo-100/50 opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 text-center">
                        
                        <p class="font-semibold text-lg mb-2" style="color: #4b0082;">Seret dan jatuhkan atau klik untuk memilih file</p>
                        <p class="text-sm text-gray-600">Format: JPG, JPEG, PNG. Maks 5MB</p>
                    </div>
                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" accept="image/*" class="hidden" x-ref="fileInput" @change="$dispatch('change')">
                </div>

                <!-- Enhanced Preview -->
                <template x-if="preview">
                    <div class="mb-6 flex justify-center">
                        <div class="relative">
                            <img :src="preview" alt="Preview" class="rounded-2xl shadow-2xl max-h-64 border-4 border-white">
                            <div class="absolute inset-0 rounded-2xl shadow-inner pointer-events-none"></div>
                        </div>
                    </div>
                </template>

                @error('bukti_pembayaran')
                    <div class="text-red-600 text-sm mb-4 p-3 bg-red-50 rounded-xl border border-red-200 flex items-center">
                        
                        {{ $message }}
                    </div>
                @enderror

                <!-- Enhanced Submit Button -->
                <button type="submit" class="w-full btn-primary text-white font-semibold py-4 px-6 rounded-2xl text-lg relative overflow-hidden">
                    <span class="relative z-10 flex items-center justify-center">
                        
                        Upload Bukti Pembayaran
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 -translate-x-full hover:translate-x-full transition-transform duration-1000"></div>
                </button>
            </form>
        </div>

        <!-- Enhanced Footer -->
        <div class="bg-gradient-to-r from-gray-50 to-purple-50 p-6 text-center border-t border-purple-100">
            <div class="flex items-center justify-center text-gray-600 text-sm">
                Silakan unggah bukti pembayaran untuk menyelesaikan transaksi Anda.
            </div>
        </div>
    </div>
</body>
</html>