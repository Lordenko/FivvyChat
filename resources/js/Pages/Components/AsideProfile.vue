<script setup>
import {ref} from 'vue'
import LogoutButton from '../Components/LogoutButton.vue';
import HamburgerButton from '../Components/HamburgerButton.vue';
import {usePage} from '@inertiajs/vue3'

const page = usePage()
const props = defineProps({
    open: Boolean
})

const emit = defineEmits(['update:open'])

function toggleMenu() {
    open = !open
    emit('update:open', !props.open)
}

</script>

<template>
    <div
        class="fixed md:static transition-transform duration-300 h-full w-[300px] bg-zinc-950 flex flex-col items-center z-50"
        :class="{ '-translate-x-full': !open, 'translate-x-0': open }"
    >
        <div class="m-2 w-[90%] flex justify-end md:hidden">
            <HamburgerButton @click="toggleMenu" />
        </div>

        <div class="w-[90%] p-5 flex flex-col justify-evenly bg-zinc-900 rounded-2xl m-1">
            <img class="rounded-full select-none w-24 h-24 object-cover" :src="'/images/default_pfp.jpg'" alt="Avatar" />
            <p class="text-2xl mt-2 text-white">{{ page.props.auth.user.name }}</p>
        </div>

        <div class="mt-2 w-[90%]">
            <button
                class="w-full py-4 rounded-2xl text-white border border-zinc-700 bg-zinc-800 transition"
                :disabled="!open"
                :class="{
                    'hover:cursor-pointer hover:bg-zinc-700': open,
                    'text-zinc-500': !open
                }"
            >
                Settings
            </button>
        </div>

        <div class="mt-2 w-[90%]">
            <LogoutButton :disabled="!open" />
        </div>
    </div>
</template>

