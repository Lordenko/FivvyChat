<script setup>
import { ref } from 'vue'
import AsideProfile from '../Components/AsideProfile.vue';
import UserCard from '../Components/UserCard.vue';

const isAsideOpen = ref(true)

</script>

<template>

    <Head title="Chat" />

    <AsideProfile v-model:open="isAsideOpen" />

    <div class="h-full grid grid-cols-[68px_300px_1fr]">
        <div class="bg-blue-200"></div>


        <div class="bg-green-200">

            <UserCard name="Lordenko" last_message="U'r best!!!" :isAsideOpen="!isAsideOpen" />
            <UserCard name="Lordenko" last_message="U'r best!!!" :isAsideOpen="!isAsideOpen" />
            <UserCard name="Lordenko" last_message="U'r best!!!" :isAsideOpen="!isAsideOpen" />
            <UserCard name="Lordenko" last_message="U'r best!!!" :isAsideOpen="!isAsideOpen" />
            <UserCard name="Lordenko" last_message="U'r best!!!" :isAsideOpen="!isAsideOpen" />
            <UserCard name="Lordenko" last_message="U'r best!!!" :isAsideOpen="!isAsideOpen" />


        </div>



        <div class="bg-blue-200">1fr
            <div> {{ isAsideOpen }} </div>

        </div>
    </div>

</template>
