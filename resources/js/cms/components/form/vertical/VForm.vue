<template>
    <form class="space-y-6" @submit.prevent :submit="() => $emit('submit')">
        <slot  />
    </form>
</template>
<!-- Parent passes down explicit submit action -->