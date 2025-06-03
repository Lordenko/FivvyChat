<script setup>
import { useForm, usePage, router } from '@inertiajs/vue3'
import { ref } from 'vue'

defineProps({
    user: Object,
    isOpen: Boolean,
    avatar_path: { type: String, default: '/images/default_pfp.jpg' },
})

const avatarInput = ref(null)

const emit = defineEmits(['update:user', 'update:isOpen'])

const updateUser = (newUser) => {
    emit('update:user', newUser)
}

const avatarForm = useForm({
    avatar: null,
})

const handleAvatarUpload = (event) => {
    avatarForm.avatar = event.target.files[0]
}

const submitAvatar = () => {
    avatarForm.post(route('profile.avatar.update'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            avatarForm.reset('avatar')
            if (avatarInput.value) avatarInput.value.value = ''
            router.reload({
                only: ['auth'],
                onSuccess: () => {
                    router.reload({
                        only: ['auth.user'],
                        onSuccess: () => {
                            emit('update:user', usePage().props.auth?.user)
                        }
                    })
                }
            })
        },
    })
}


const nicknameForm = useForm({
    name: '',
})

const submitNickname = () => {
    nicknameForm.post(route('profile.nickname.update'), {
        preserveScroll: true,
        onSuccess: () => {
            nicknameForm.reset()
            router.reload({
                only: ['auth.user'],
                onSuccess: () => {
                    emit('update:user', usePage().props.auth?.user)
                }
            })
        },
    })
}

const passwordForm = useForm({
    current_password: '',
    new_password: '',
    confirm_password: '',
})

const submitPassword = () => {
    passwordForm.post(route('profile.password.update'), {
        preserveScroll: true,
        onSuccess: () => passwordForm.reset(),
    })
}
</script>

<template>

    <div
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm backdrop-brightness-75 p-4"
        @click.self="$emit('update:isOpen', false)"
    >
        <div class="bg-zinc-900 text-white w-full max-w-md rounded-2xl p-6 shadow-2xl border border-zinc-700 max-h-[90vh] scrollbar-hidden overflow-y-auto">
            <slot />

            <!-- Інформація про користувача -->
            <div class="flex items-center space-x-3 mb-5">
                <img
                    :src="user.avatar_path ? `/storage/${user.avatar_path}` : '/images/default_pfp.jpg'"
                    alt="Аватар користувача"
                    class="size-14 rounded-full object-cover border-2 border-purple-500 shadow-lg transition-transform duration-300 hover:scale-105"
                />
                <div class="text-lg font-semibold text-purple-300 truncate">{{ user.name }}</div>
            </div>

            <!-- Завантаження аватара -->
            <form @submit.prevent="submitAvatar" class="space-y-2 mb-5" enctype="multipart/form-data">
                <div class="text-base font-medium text-purple-300">🖼️ Змінити аватар</div>
                <label class="block text-sm text-zinc-400">Завантажити новий аватар (PNG, JPG)</label>
                <input
                    ref="avatarInput"
                    type="file"
                    accept="image/*"
                    @change="handleAvatarUpload"
                    class="w-full text-sm text-zinc-300 file:mr-4 file:py-2 file:px-4
           file:rounded-full file:border-0
           file:text-sm file:font-semibold
           file:bg-purple-700 file:text-white
           hover:file:bg-purple-800 transition" />
                <p v-if="avatarForm.errors.avatar" class="text-sm text-red-400">{{ avatarForm.errors.avatar }}</p>
                <button
                    type="submit"
                    class="w-full py-2 bg-purple-700 hover:bg-purple-800 rounded text-white font-semibold transition">
                    Завантажити
                </button>
            </form>

            <!-- Нікнейм -->
            <form @submit.prevent="submitNickname" class="space-y-2 mb-5">
                <div class="text-base font-medium text-purple-300">🔤 Змінити нікнейм</div>
                <label class="block text-sm text-zinc-400">Введіть новий нікнейм</label>
                <input
                    v-model="nicknameForm.name"
                    type="text"
                    class="w-full p-2 rounded bg-zinc-800 text-white border border-zinc-600 focus:outline-none focus:ring-2 focus:ring-purple-500" />
                <p v-if="nicknameForm.errors.name" class="text-sm text-red-400">{{ nicknameForm.errors.name }}</p>
                <button
                    type="submit"
                    class="w-full py-2 bg-purple-700 hover:bg-purple-800 rounded text-white font-semibold transition">
                    Змінити
                </button>
            </form>

            <!-- Пароль -->
            <form @submit.prevent="submitPassword" class="space-y-2">
                <div class="text-base font-medium text-purple-300">🔒 Змінити пароль</div>

                <!-- приховане поле для автозаповнення -->
                <input
                    type="text"
                    name="name"
                    autocomplete="username"
                    :value="user.name"
                    class="hidden"
                />

                <label class="block text-sm text-zinc-400">Введіть старий пароль</label>
                <input
                    v-model="passwordForm.current_password"
                    type="password"
                    class="w-full p-2 rounded bg-zinc-800 text-white border border-zinc-600 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    autocomplete="current-password" />
                <p v-if="passwordForm.errors.current_password" class="text-sm text-red-400">{{ passwordForm.errors.current_password }}</p>

                <label class="block text-sm text-zinc-400">Введіть новий пароль</label>
                <input
                    v-model="passwordForm.new_password"
                    type="password"
                    class="w-full p-2 rounded bg-zinc-800 text-white border border-zinc-600 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    autocomplete="new-password" />
                <p v-if="passwordForm.errors.new_password" class="text-sm text-red-400">{{ passwordForm.errors.new_password }}</p>

                <label class="block text-sm text-zinc-400">Підтвердіть новий пароль</label>
                <input
                    v-model="passwordForm.confirm_password"
                    type="password"
                    class="w-full p-2 rounded bg-zinc-800 text-white border border-zinc-600 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    autocomplete="new-password" />
                <p v-if="passwordForm.errors.confirm_password" class="text-sm text-red-400">{{ passwordForm.errors.confirm_password }}</p>

                <button
                    type="submit"
                    class="w-full py-2 bg-purple-700 hover:bg-purple-800 rounded text-white font-semibold transition">
                    Змінити
                </button>
            </form>

        </div>
    </div>
</template>
