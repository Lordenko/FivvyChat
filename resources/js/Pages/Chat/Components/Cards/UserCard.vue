<script setup>
import { router } from '@inertiajs/vue3'

defineProps({
    data: {
        type: Object,
        required: true
    },
    user: {
        type: Object,
        required: true
    },
    current_user: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <Link
        :href="route('chats.store')"
        method="post"
        :data="data"
        as="button"
        v-if="user.id !== current_user.id"
    >
        {{ user.name }}
    </Link>
</template>
