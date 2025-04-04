<template>
    <form class="space-y-4" @submit.prevent :submit="() => $emit('submit')">
        <slot/>
    </form>
</template>
<!-- Parent passes down explicit submit action -->