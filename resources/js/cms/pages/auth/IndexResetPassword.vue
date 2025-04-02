<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100 p-6">
        <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Reset Password</h1>
            <form class="space-y-6">
                <!--Email-->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        placeholder="Enter your email" required />
                </div>

                <!-- Reset Password Link -->
                <div class="text-center">
                    <router-link :to="{ name: 'auth.login' }"
                        class="text-sm text-indigo-600 hover:text-indigo-800">
                        Back To Login
                    </router-link>
                </div>

                <!-- Reset Password Button -->
                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
</script>