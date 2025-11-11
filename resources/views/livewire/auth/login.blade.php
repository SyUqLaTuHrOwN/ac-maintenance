<div class="min-h-[calc(100vh-140px)] flex items-center justify-center px-4">
  <div class="w-full max-w-md bg-white border rounded-2xl p-6 md:p-8">
    <h1 class="text-2xl font-semibold">Masuk</h1>
    <form class="mt-6 grid gap-5" wire:submit="submit">
      <div>
        <label class="text-sm">Email</label>
        <input type="email" wire:model.defer="email" class="mt-2 w-full rounded-xl border-gray-300"
               autocomplete="username" required placeholder="nama@perusahaan.com">
        @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
      </div>
      <div>
        <label class="text-sm">Kata sandi</label>
        <input type="password" wire:model.defer="password" class="mt-2 w-full rounded-xl border-gray-300"
               autocomplete="current-password" required placeholder="••••••••">
        @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
      </div>
      <label class="inline-flex items-center gap-2 text-sm">
        <input type="checkbox" wire:model="remember" class="rounded border-gray-300"> Ingat saya
      </label>
      <button type="submit" class="w-full rounded-xl bg-indigo-600 text-white py-3">Masuk</button>
    </form>
  </div>
</div>
