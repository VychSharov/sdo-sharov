<script setup>
import { ref, onMounted } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import SideNavItem from "../Components/SideNavItem.vue";
import MenuIcon from "vue-material-design-icons/Menu.vue";
import MagnifyIcon from "vue-material-design-icons/Magnify.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

let openSideNav = ref(true);
let openSideNavOverlay = ref(false);
let sideNavOverlay = ref(null);
let width = ref(document.documentElement.clientWidth);

const searchQuery = ref('');
const searchResults = ref([]);

const searchCourses = async () => {
    try {
        const response = await axios.get(route('courses.search'), {
            params: {
                query: searchQuery.value
            }
        });
        searchResults.value = response.data;
    } catch (error) {
        console.error('Error fetching search results:', error);
    }
};

onMounted(() => {
    resize();
    sideNavOverlay.value.classList.value =
        sideNavOverlay.value.classList.value += " hidden";
    window.addEventListener("resize", () => {
        width.value = document.documentElement.clientWidth;
        resize();
    });
});

const resize = () => {
    if (width.value < 1280 && openSideNav.value) {
        openSideNav.value = false;
    }
    if (width.value > 1279 && !openSideNav.value) {
        openSideNav.value = true;
    }
};

const isNavOverlay = () => {
    if (usePage().url === "/") openSideNav.value = !openSideNav.value;
    if (usePage().url === "/add-course")
        openSideNavOverlay.value = !openSideNavOverlay.value;
    if (usePage().url === "/manage-course")
        openSideNavOverlay.value = !openSideNavOverlay.value;
    if (usePage().url === "/manage-students")
        openSideNavOverlay.value = !openSideNavOverlay.value;
    if (usePage().url === "/add-quiz")
        openSideNavOverlay.value = !openSideNavOverlay.value;
    if (usePage().url === "/manage-quiz")
        openSideNavOverlay.value = !openSideNavOverlay.value;
    if (usePage().url === "/quizzes")
        openSideNavOverlay.value = !openSideNavOverlay.value;
    if (usePage().url === "/quizzes")
        openSideNavOverlay.value = !openSideNavOverlay.value;
    if (usePage().url === "/my-courses")
        openSideNavOverlay.value = !openSideNavOverlay.value;
    if (width.value < 640) openSideNavOverlay.value = !openSideNavOverlay.value;
    if (usePage().url !== "/" && width.value < 640)
        openSideNavOverlay.value = !openSideNavOverlay.value;
    if (usePage().props.course)
        openSideNavOverlay.value = !openSideNavOverlay.value;
    if (usePage().props.episode_details)
        openSideNavOverlay.value = !openSideNavOverlay.value;
    if (usePage().props.student)
        openSideNavOverlay.value = !openSideNavOverlay.value;
};
</script>

<template>
    <div class="relative">
        <div
            id="TopNav"
            class="w-[100%] h-[60px] fixed bg-white z-20 flex items-center justify-between border-b-2 border-gray-100"
        >
            <div class="flex items-center">
                <button
                    @click="isNavOverlay()"
                    class="p-2 ml-3 rounded-full hover:bg-white inline-block cursor-pointer"
                >
                    <MenuIcon fillColor="#1f467a" :size="26" />
                </button>
                <div class="mx-2"></div>
                <Link
                    :href="route('home')"
                    class="flex items-center justify-center mr-10 cursor-pointer ml-2"
                >
                    <img width="45" src="/images/logo.png" alt="" />
                </Link>
            </div>

            <div class="w-[600px] md:block hidden">
                <div class="rounded-full flex items-center bg-[#1f467a] relative">
                    <input
                        type="text"
                        v-model="searchQuery"
                        @input="searchCourses"
                        class="form-control block w-full px-5 py-1.5 text-base font-normal text-gray-700 bg-gray-100 placeholder-gray-500 bg-clip-padding border border-solid border-l-[#1f467a] border-y-[#1f467a] rounded-l-full transition ease-in-out m-0 border-transparent focus:ring-0"
                        placeholder="Поиск курса"
                    />
                    <MagnifyIcon class="mx-6" fillColor="#FFFFFF" :size="23" />
                    <div v-if="searchQuery.length > 0" class="absolute top-full left-0 w-full bg-white border border-t-0 border-gray-300 z-10 rounded-b-lg">
                        <ul>
                            <li v-if="searchResults.length === 0" class="p-2 text-gray-500">Ничего не найдено</li>
                            <li v-for="course in searchResults" :key="course.id" class="p-2 hover:bg-gray-100">
                                <Link :href="route('course.show', { id: course.id })">
                                    {{ course.title }}
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <Dropdown align="right" width="48">
                <template #trigger>
                    <span class="inline-flex rounded-md">
                        <button
                            type="button"
                            class=" me-8 inline-flex items-center px-3 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#1f467a] hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
                        >
                            {{ $page.props.auth.user.name }}

                            <div>
                                <img
                                    v-if="$page.props.auth.user.role != 'admin'"
                                    class="rounded-full ml-2"
                                    width="35"
                                    src="/images/astronaut.png"
                                />
                                <img
                                    v-else
                                    class="rounded-full ml-2 bg-slate-400"
                                    width="35"
                                    src="/images/admin.png"
                                />
                            </div>
                        </button>
                    </span>
                </template>

                <template #content>
                    <DropdownLink :href="route('profile.edit')">
                        Профиль
                    </DropdownLink>
                    <DropdownLink
                        :href="route('logout')"
                        method="post"
                        as="button"
                    >
                        Выйти
                    </DropdownLink>
                </template>
            </Dropdown>
        </div>
        <div
            @click="openSideNavOverlay = false"
            class="bg-[#edf1f5] bg-opacity-70 fixed z-50 w-full h-screen "
            :class="
                openSideNavOverlay
                    ? 'animate__animated animate__fadeIn animate__faster'
                    : 'hidden z-[-1]'
            "
        />
        <div v-if="width > 639" >
            <div
                v-if="$page.url === '/'"
                id="SideNav"
                :class="[!openSideNav ? 'w-[70px]' : 'w-[260px]']"
                class="h-[100%] fixed z-0 bg-[#edf1f5] "

            >
                <ul
                    :class="[!openSideNav ? 'p-2' : 'px-5 pb-2 pt-[7px]']"
                    class="mt-[60px] w-full "
                >
                    <Link :href="route('home')">
                        <SideNavItem
                            :openSideNav="openSideNav"
                            iconString="Главная"
                            class=" hover:bg-white inline-block cursor-pointer"
                        />
                    </Link>

                    <div v-if="$page.props.auth.user.role == 'admin'" >
                        <div class="border-b border-slate-700 my-2.5 "></div>
                        <Link :href="route('addCourse')">
                            <SideNavItem
                                :openSideNav="openSideNav"
                                iconString="Создать курс"
                                class="hover:bg-white inline-block cursor-pointer"
                            />
                        </Link>
                        <Link :href="route('manageCourses')">
                            <SideNavItem
                                :openSideNav="openSideNav"
                                iconString="Управление курсами"
                                class="hover:bg-white inline-block cursor-pointer"
                            />
                        </Link>
                        <Link :href="route('manageStudents')">
                            <SideNavItem
                                :openSideNav="openSideNav"
                                iconString="Управление студентами"
                                class="hover:bg-white inline-block cursor-pointer"
                            />
                        </Link>
                        <Link :href="route('addQuiz')">
                            <SideNavItem
                                :openSideNav="openSideNav"
                                iconString="Создать тест"
                                class="hover:bg-white inline-block cursor-pointer"
                            />
                        </Link>
                        <Link :href="route('manageQuiz')">
                            <SideNavItem
                                :openSideNav="openSideNav"
                                iconString="Управление тестами"
                                class="hover:bg-white inline-block cursor-pointer"
                            />
                        </Link>
                        <Link :href="route('quizzes')">
                            <SideNavItem
                                :openSideNav="openSideNav"
                                iconString="Показать тесты"
                                class="hover:bg-white inline-block cursor-pointer"
                            />
                        </Link>
                    </div>

                    <div v-if="$page.props.auth.user.role == ''" >
                        <div class="border-b border-slate-700 my-2.5 "></div>
                        <Link :href="route('addCourse')">
                            <SideNavItem
                                :openSideNav="openSideNav"
                                iconString="Создать курс"
                                class="hover:bg-white inline-block cursor-pointer"
                            />
                        </Link>
                        <Link :href="route('manageCourses')">
                            <SideNavItem
                                :openSideNav="openSideNav"
                                iconString="Управление курсами"
                                class="hover:bg-white inline-block cursor-pointer"
                            />
                        </Link>
                        <Link :href="route('manageStudents')">
                            <SideNavItem
                                :openSideNav="openSideNav"
                                iconString="Управление студентами"
                                class="hover:bg-white inline-block cursor-pointer"
                            />
                        </Link>
                        <Link :href="route('addQuiz')">
                            <SideNavItem
                                :openSideNav="openSideNav"
                                iconString="Создать тест"
                                class="hover:bg-white inline-block cursor-pointer"
                            />
                        </Link>
                        <Link :href="route('manageQuiz')">
                            <SideNavItem
                                :openSideNav="openSideNav"
                                iconString="Управление тестами"
                                class="hover:bg-white inline-block cursor-pointer"
                            />
                        </Link>
                        <Link :href="route('quizzes')">
                            <SideNavItem
                                :openSideNav="openSideNav"
                                iconString="Показать тесты"
                                class="hover:bg-white inline-block cursor-pointer"
                            />
                        </Link>
                    </div>

                    <div class="border-b border-slate-700 my-2.5"></div>
                    <Link :href="route('my.courses')">
                            <SideNavItem
                            :openSideNav="openSideNav"
                            iconString="Мои курсы"
                            class="hover:bg-white inline-block cursor-pointer"
                        />
                        </Link>
                    <!-- <SideNavItem
                        :openSideNav="openSideNav"
                        iconString="Мои курсы"
                        class="text-blue-900  hover:bg-white inline-block cursor-pointer"
                    /> -->
                    <!-- <SideNavItem
                        :openSideNav="openSideNav"
                        iconString="My Library"
                        class="text-[#1f467a]"
                    /> -->
                </ul>
            </div>
        </div>

        <!-- OVERLAY NAV SECTION -->

        <div
            id="SideNavOverlay"
            ref="sideNavOverlay"
            class="h-[100%] fixed z-50 bg-[#edf1f5] mt-[0px] w-[260px]"
            :class="
                openSideNavOverlay
                    ? 'animate__animated animate__slideInLeft animate__faster'
                    : 'animate__animated animate__slideOutLeft animate__faster'
            "
        >
            <div class="flex items-center">
                <button
                    @click="isNavOverlay()"
                    class="p-2 ml-3 rounded-full hover:bg-white cursor-pointer mt-[20px]"
                >
                    <MenuIcon fillColor="#1f467a" :size="26" />
                </button>
                <div class="mx-2"></div>
                <Link
                    :href="route('home')"
                    class="flex items-center justify-center cursor-pointer mt-[20px]"
                >
                    <img width="45" src="/images/logo.png" alt="" />
                </Link>
            </div>
             <ul
                    :class="[!openSideNav ? 'p-2' : 'px-5 pb-2 pt-[7px]']"
                    class="mt-[20px] w-full "
                >
                    <Link :href="route('home')">
                        <SideNavItem
                            :openSideNav="openSideNav"
                            iconString="Главная"
                            class="hover:bg-white inline-block cursor-pointer"
                        />
                    </Link>

                    <div v-if="$page.props.auth.user.role == 'admin'" >
                        <div class="border-b border-slate-700 my-2.5 "></div>
                        <Link :href="route('addCourse')">
                            <SideNavItem
                                :openSideNav="openSideNav"
                                iconString="Создать курс"
                                class="hover:bg-white inline-block cursor-pointer"
                            />
                        </Link>
                        <Link :href="route('manageCourses')">
                            <SideNavItem
                                :openSideNav="openSideNav"
                                iconString="Управление курсами"
                                class="hover:bg-white inline-block cursor-pointer"
                            />
                        </Link>
                        <Link :href="route('manageStudents')">
                            <SideNavItem
                                :openSideNav="openSideNav"
                                iconString="Управление студентами"
                                class="hover:bg-white inline-block cursor-pointer"
                            />
                        </Link>
                        <Link :href="route('addQuiz')">
                            <SideNavItem
                                :openSideNav="openSideNav"
                                iconString="Создать тест"
                                class="hover:bg-white inline-block cursor-pointer"
                            />
                        </Link>
                        <Link :href="route('manageQuiz')">
                            <SideNavItem
                                :openSideNav="openSideNav"
                                iconString="Управление тестами"
                                class="hover:bg-white inline-block cursor-pointer"
                            />
                        </Link>
                        <Link :href="route('quizzes')">
                            <SideNavItem
                                :openSideNav="openSideNav"
                                iconString="Показать тесты"
                                class="hover:bg-white inline-block cursor-pointer"
                            />
                        </Link>
                    </div>
                    <div class="border-b border-slate-700 my-2.5"></div>
                    <Link :href="route('my.courses')">
                            <SideNavItem
                            :openSideNav="openSideNav"
                            iconString="Мои курсы"
                            class="hover:bg-white inline-block cursor-pointer"
                        />
                        </Link>
                    <!-- <SideNavItem
                        :openSideNav="openSideNav"
                        iconString="My Library"
                        class="text-[#1f467a]"
                    /> -->
                </ul>
        </div>
        <!-- OVERLAY NAV SECTION END -->

        <div
            class="w-[100%] h-[calc(100vh-60px)] absolute right-0 top-[70px]"
            :class="{
                'w-[calc(100%-90px)]': !openSideNav,
                'w-[calc(100%-280px)]': openSideNav,
                'w-[100vw] xl:w-[calc(100%-80px)]': $page.url !== '/',
                'w-[100vw]': width < 639,
            }"
        >
            <slot />
        </div>
    </div>
</template>

<style>
body {
    background-color: white;
}
</style>
