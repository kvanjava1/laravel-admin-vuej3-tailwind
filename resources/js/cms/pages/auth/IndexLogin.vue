<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100 p-6">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-lg">
      <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Login</h1>
      <AlertBox :message="message" />
      <VForm @submit="clickToLogin">
        <VFormItem>
          <VFormLabel label="Email" />
          <VFormInput v-model="inputLogin.email" type="text" placeholder="Who are you?" name="email" required />
        </VFormItem>
        <VFormItem>
          <VFormLabel label="Password" />
          <VFormInput v-model="inputLogin.password" type="password" placeholder="Your password" name="username"
            :required="true" />
        </VFormItem>
        <VFormItem class="text-center">
          <router-link :to="{ name: 'auth.reset-password' }">
            <label class="text-green-600 capitalize">forgot password your ?</label>
          </router-link>
        </VFormItem>
        <VFormItem>
          <VerticalMenu>
            <Button :disabled="loading.login" class="w-full">
              <ArrowRightOnRectangleIcon class="w-5 h-5" />
              Login
            </Button>
          </VerticalMenu>
        </VFormItem>
      </VForm>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

import type { LoginTypes } from '@/cms/types/auth.d';
import type { MessageTypes } from '@/cms/types/message';

import { useAuth } from '@/cms/composables/useAuth'
import AlertBox from '@/cms/components/AlertBox.vue'

import { useRouter } from 'vue-router'
import VForm from '@/cms/components/form/vertical/VForm.vue';
import VFormItem from '@/cms/components/form/vertical/VFormItem.vue';
import VFormLabel from '@/cms/components/form/vertical/VFormLabel.vue';
import VFormInput from '@/cms/components/form/vertical/VFormInput.vue';
import VerticalMenu from '@/cms/components/VerticalMenu.vue';
import Button from '@/cms/components/Button.vue';
import { ArrowRightOnRectangleIcon } from '@heroicons/vue/20/solid';

const inputLogin = ref<LoginTypes>({} as LoginTypes)
const { login, loading } = useAuth();
const message = ref<MessageTypes>({} as MessageTypes)
const router = useRouter();

const clickToLogin = async (): Promise<void> => {
  const responseLogin = await login(inputLogin.value)
  message.value = responseLogin

  if (message.value.code === 'success') {
    setTimeout(() => {
      router.push({ name: 'dashboard.index' })
    }, 500)

  }
}
</script>
