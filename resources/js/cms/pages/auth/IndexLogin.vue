<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100 p-6">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-lg">
      <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Login</h1>
      <AlertBox :message="message" />
      <form class="space-y-6">
        <div>
          <label for="username" class="block text-sm font-medium text-gray-700">Email</label>
          <input required type="text"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            v-model="inputLogin.email" placeholder="Enter your email"  />
        </div>
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input required type="password"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            placeholder="Enter your password" v-model="inputLogin.password"  />
        </div>
        <div class="text-center">
          <router-link :to="{ name: 'auth.reset-password' }" class="text-sm text-indigo-600 hover:text-indigo-800">
            Forgot your password?
          </router-link>
        </div>
        <div>
          <button @click.prevent="clickToLogin" :disabled="loading.login"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white transition duration-200 ease-in-out bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:bg-gray-400 disabled:cursor-not-allowed">
            Login
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref} from 'vue'

import type { LoginTypes } from '@/cms/types/auth.d';
import type { MessageTypes } from '@/cms/types/message';

import { useAuth } from '@/cms/composables/useAuth'
import AlertBox from '@/cms/components/AlertBox.vue'

import { useRouter } from 'vue-router'

const inputLogin = ref<LoginTypes>({} as LoginTypes)
const { login, loading } = useAuth();
const message  = ref<MessageTypes>({} as MessageTypes)
const router = useRouter();

const clickToLogin = async () : Promise<void> => {
  const loginProc = await login(inputLogin.value)
  message.value = loginProc

  if (message.value.code === 'success') {
      setTimeout(() => {
          router.push({ name: 'dashboard.index' })
      }, 500)
      
  }
}
</script>
