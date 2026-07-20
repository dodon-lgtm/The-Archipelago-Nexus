<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Penawaran</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body{
            font-family:'Plus Jakarta Sans',sans-serif;
        }
    </style>
</head>

<body class="bg-slate-100">

@include('navbar.nav')

<div class="max-w-7xl mx-auto py-10 px-6">

    <a href="{{ route('freelancer.projects.show',$project->id) }}"
       class="inline-flex items-center gap-2 text-cyan-600 font-semibold mb-6">
        <i class="fa fa-arrow-left"></i>
        Kembali ke Detail
    </a>

    <div class="grid lg:grid-cols-3 gap-8">

        <!-- FORM -->
        <div class="lg:col-span-2">

            <div class="bg-white rounded-2xl shadow-lg p-8">

                <h2 class="text-2xl font-bold mb-6">
                    Kirim Penawaran
                </h2>

                <form
                    action="{{ route('freelancer.penawaran.store',$project) }}"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf

                    <div class="mb-6">

                        <label class="font-semibold block mb-2">
                            Harga Penawaran
                        </label>

                        <input
                            type="number"
                            name="harga_penawaran"
                            class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500"
                            placeholder="Contoh : 4500000">

                    </div>

                    <div class="mb-6">

                        <label class="font-semibold block mb-2">
                            Estimasi Pengerjaan (Hari)
                        </label>

                        <input
                            type="number"
                            name="estimasi_hari"
                            class="w-full border rounded-xl px-4 py-3"
                            placeholder="Misal : 14">

                    </div>

                    <div class="mb-6">

                        <label class="font-semibold block mb-2">
                            Pesan Kepada Perusahaan
                        </label>

                        <textarea
                            name="pesan"
                            rows="7"
                            class="w-full border rounded-xl px-4 py-3"
                            placeholder="Perkenalkan diri dan jelaskan mengapa Anda cocok mengerjakan proyek ini..."></textarea>

                    </div>

                    <div class="mb-8">

                        <label class="font-semibold block mb-2">
                            Upload Proposal (PDF)
                        </label>

                        <input
                            type="file"
                            name="proposal"
                            accept=".pdf"
                            class="w-full border rounded-xl p-3">

                    </div>

                    <button
                        class="bg-cyan-600 hover:bg-cyan-700 text-white px-8 py-3 rounded-xl font-bold">

                        <i class="fa fa-paper-plane mr-2"></i>

                        Kirim Penawaran

                    </button>

                </form>

            </div>

        </div>

        <!-- SIDEBAR -->
        <div>

            <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-6">

                <img
                    src="{{ asset('storage/'.$project->image) }}"
                    class="rounded-xl h-48 w-full object-cover">

                <h2 class="font-bold text-xl mt-5">

                    {{ $project->project_name }}

                </h2>

                <div class="mt-5 space-y-4">

                    <div>
                        <p class="text-slate-500 text-sm">
                            Budget
                        </p>

                        <h3 class="font-bold text-cyan-600 text-2xl">
                            Rp {{ number_format($project->budget,0,',','.') }}
                        </h3>
                    </div>

                    <hr>

                    <div>
                        <p class="text-slate-500 text-sm">
                            Deadline
                        </p>

                        <h3 class="font-semibold">
                            {{ $project->deadline }}
                        </h3>
                    </div>

                    <hr>

                    <div>
                        <p class="text-slate-500 text-sm">
                            Perusahaan
                        </p>

                        <h3 class="font-semibold">
                            {{ $project->owner->name }}
                        </h3>
                    </div>

                    <hr>

                    <div>
                        <p class="text-slate-500 text-sm">
                            Status
                        </p>

                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                            {{ $project->status }}
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>