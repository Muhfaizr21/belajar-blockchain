<!DOCTYPE html>
<html>
<head>
  <title>Expense Tracker Blockchain</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
  <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Tambah Pengeluaran</h1>

    <form action="{{ route('expenses.store') }}" method="POST" class="space-y-3">
      @csrf
      <input type="text" name="title" placeholder="Judul" required class="w-full border p-2 rounded">
      <input type="number" name="amount" placeholder="Jumlah" required class="w-full border p-2 rounded">
      <input type="date" name="spent_at" required class="w-full border p-2 rounded">
      <textarea name="description" placeholder="Deskripsi" class="w-full border p-2 rounded"></textarea>
      <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
    </form>

    @if(session('success'))
      <div class="bg-green-100 text-green-700 p-3 mt-4 rounded">{{ session('success') }}</div>
    @endif

    <h2 class="text-xl font-bold mt-6">Daftar Pengeluaran</h2>
    <ul class="divide-y divide-gray-200 mt-2">
      @foreach($expenses as $e)
        <li class="py-2">
          <strong>{{ $e->title }}</strong> — Rp{{ number_format($e->amount) }}
          <br><small>{{ $e->spent_at }}</small>
          @if($e->blockchain_tx)
            <br><a href="https://mumbai.polygonscan.com/tx/{{ $e->blockchain_tx }}" target="_blank" class="text-blue-500">Lihat di Blockchain</a>
          @endif
        </li>
      @endforeach
    </ul>
  </div>
</body>
</html>
