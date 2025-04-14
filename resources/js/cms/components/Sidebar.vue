<template>
  <aside ref="sidebarRef" :class="[
    'w-70 bg-gray-800 shadow-lg p-4 fixed top-0 left-0 z-40 h-screen',
    { '-translate-x-full lg:translate-x-0': !isOpen }
  ]">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-white">COREW</h1>
    </div>
    <nav>
      <ul>
        <li class="mb-3">
          <router-link :to="{name: 'dashboard.index'}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded transition-all">
            <Bars3Icon class="w-5 h-5 mr-3" />
            <span>Dashboard</span>
          </router-link>
        </li>
        <li class="mb-3">
          <router-link :to="{ name: 'categorymanagement.index'}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded transition-all">
            <Bars3Icon class="w-5 h-5 mr-3" />
            <span>Category Management</span>
          </router-link>
        </li>
        <li class="mb-3">
          <a href="#" @click.prevent="toggleDropdown('usermanagement')"
            class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded transition-all">
            <Bars3Icon class="w-5 h-5 mr-3" />
            <span>Users Management</span>
            <ChevronDownIcon class="w-4 h-4 ml-auto" />
          </a>
          <ul v-show="dropdowns.usermanagement" class="pl-4 mt-2">
            <li class="mb-2">
              <router-link :to="{ 'name': 'usermanagement.role.index' }"
                class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded">Roles</router-link>
            </li>
            <li class="mb-2">
              <router-link :to="{ 'name': 'usermanagement.user.index' }"
                class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded">Users</router-link>
            </li>
          </ul>
        </li>
        <li class="mb-3">
          <a href="#" @click.prevent="toggleDropdown('myaccount')"
            class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded transition-all">
            <Bars3Icon class="w-5 h-5 mr-3" />
            <span>My Account</span>
            <ChevronDownIcon class="w-4 h-4 ml-auto" />
          </a>
          <ul v-show="dropdowns.myaccount" class="pl-4 mt-2">
            <li class="mb-2">
              <router-link :to="{ name: 'account.profile.index'}"
                class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded">Profile</router-link>
            </li>
            <li class="mb-2">
              <a @click.prevent="clickToLogout"
                class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded">Logout</a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </aside>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Bars3Icon, ChevronDownIcon } from '@heroicons/vue/24/outline';
import { useAuth } from '@/cms/composables/useAuth';
import { useRouter } from 'vue-router';

type menuType = 'myaccount' | 'usermanagement';
type menuValueType = { [key: string]: boolean };

const { logout } = useAuth();
const router = useRouter();
const dropdowns = ref<menuValueType>({
  usermanagement: false,
  myaccount: false,
});
const toggleDropdown = (key: menuType): void => {
  dropdowns.value[key] = !dropdowns.value[key];
};
const sidebarRef = ref<HTMLElement | null>(null);

defineEmits<{
  (e: 'update:isOpen', value: boolean): void;
}>();
const clickToLogout = (): void => {
  if (logout()) {
    router.push({ name: 'auth.login' });
  }
};

defineProps<{
  isOpen: boolean;
}>();
defineExpose({ sidebarRef });
</script>