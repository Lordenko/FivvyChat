<script setup>
import { ref } from 'vue'
import LogoutButton from '../Components/LogoutButton.vue';
import HamburgerButton from '../Components/HamburgerButton.vue';

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
    <div class="absolute transition-all duration-400 h-full w-[300px] bg-red-200 flex flex-col items-center"
        :class="{ '-translate-x-58': !open }">
        <div class="m-2 w-9/10 flex justify-end">
            <HamburgerButton @click="toggleMenu" />
        </div>

        <div class="w-9/10 p-5 flex flex-col justify-evenly bg-black rounded-[20px] m-1">
            <img class="rounded-full select-none w-25" :src="'/images/default_pfp.jpg'" alt="Avatar" />
            <p class="text-2xl mt-2 text-white ">Lordenko</p>
        </div>

        <div class="mt-2 w-9/10">
            <button class="border select-none w-full py-4 rounded-[20px] bg-black text-white transition"
                :disabled="!open" :class="{
                    'hover:cursor-pointer hover:bg-gray-900': open
                }">
                Settings
            </button>
        </div>

        <div class="mt-2 w-9/10">
            <LogoutButton :disabled="!open" />
        </div>
    </div>

</template>
