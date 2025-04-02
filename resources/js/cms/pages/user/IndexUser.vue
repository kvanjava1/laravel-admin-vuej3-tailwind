<template>
  <Dashboard title="User" breadcrumb="Index">
    <ContentBox title="Roles List">
      <VerticalMenu>
        <router-link :to="{ 'name': 'usermanagement.user.add' }">
          <Button>
            <PlusIcon class="w-5 h-5" />
            Add
          </Button>
        </router-link>
      </VerticalMenu>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">No</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Name</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Email</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Created At</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Update At</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr>
            </tr>
          </tbody>
        </table>
      </div>
    </ContentBox>
  </Dashboard>
</template>

<script setup lang="ts">
import { PlusIcon } from '@heroicons/vue/24/outline';

import Dashboard from '@/cms/layouts/Dashboard.vue';
import ContentBox from '@/cms/components/ContentBox.vue';
import VerticalMenu from '@/cms/components/VerticalMenu.vue';
import Button from '@/cms/components/Button.vue';
</script>