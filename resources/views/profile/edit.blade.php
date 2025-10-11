<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h2>Profile Information</h2>
                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')
                    <div>
                        <label for="name">Name</label>
                        <input id="name" name="name" type="text" value="{{ auth()->user()->name }}" required />
                    </div>
                    <div>
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ auth()->user()->email }}" required />
                    </div>
                    <button type="submit">Save</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
