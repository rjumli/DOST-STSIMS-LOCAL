<template>
    <form class="app-search d-none d-md-block" style="margin-top: -25px;">
        <div class="position-relative">
            <input type="text" class="form-control" placeholder="Search..." autocomplete="off" id="search-options" />
            <span class="mdi mdi-magnify search-widget-icon"></span>
            <span @click="scholar = null" class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none" id="search-close-options"></span>
        </div>
        <div class="dropdown-menu dropdown-menu-lg" id="search-dropdown">
            <SimpleBar data-simplebar style="max-height: height: calc(100vh/2 - 326px)">
                <div class="notification-list">
                    <b-link @click="chooseScholar(list)" v-for="(list, index) of names" :key="index" class="d-flex dropdown-item notify-item py-2">
                        <img :src="currentUrl+'/imagess/avatars/'+list.profile.avatar" class="me-3 rounded-circle avatar-xs" alt="user-pic" />
                        <div class="flex-1">
                            <h6 class="m-0">{{ list.profile.name}}</h6>
                            <span class="fs-11 mb-0 text-muted">{{list.program.name}}</span>
                        </div>
                    </b-link>
                </div>
            </SimpleBar>
        </div>
    </form>
    <Course ref="course" @clear="clear"/>
</template>
<script>
import Course from './Modals/Course';
export default {
    components: { Course },
    data(){
        return {
            currentUrl: window.location.origin,
            names: [],
            scholar: {education: {school: {} ,course:{}, subcourse:{}}},
            keyword: null
        }
    },
    mounted() {
        this.isCustomDropdown();
    },
    methods: {
        checkSearchStr: _.debounce(function (string) {
            this.keyword = string;
            this.search();
        }, 500),
        search(){
            axios.get('/enrollments', {
                params: {
                    keyword: this.keyword,
                    option: 'search'
                }
            })
            .then(response => {
                if(response){ this.names = response.data.data; }
            })
            .catch(err => console.log(err));
        },
        chooseScholar(data){
            this.scholar = data;
            this.$emit('set',data);
            if (this.scholar.education.subcourse == null) {
                if(this.scholar.education.school.semester){
                    this.$refs.course.set(this.scholar);
                    this.show = 'course';
                }
            }else{
                if(this.show != 'enroll'){
                    this.$emit('show',true);
                }
            }
        },  
        clear(){
            this.$emit('set',null);
        },
        isCustomDropdown() {
            var searchOptions = document.getElementById("search-close-options");
            var dropdown = document.getElementById("search-dropdown");
            var searchInput = document.getElementById("search-options");

            searchInput.addEventListener("focus", () => {
                var inputLength = searchInput.value.length;
                if (inputLength > 0) {
                    dropdown.classList.add("show");
                    searchOptions.classList.remove("d-none");
                } else {
                    dropdown.classList.remove("show");
                    searchOptions.classList.add("d-none");
                }
            });

            searchInput.addEventListener("keyup", () => {
                var inputLength = searchInput.value.length;
                if (inputLength > 0) {
                    dropdown.classList.add("show");
                    searchOptions.classList.remove("d-none");
                    this.checkSearchStr(searchInput.value);
                } else {
                    dropdown.classList.remove("show");
                    searchOptions.classList.add("d-none");
                }
            });

            searchOptions.addEventListener("click", () => {
                searchInput.value = "";
                dropdown.classList.remove("show");
                searchOptions.classList.add("d-none");
            });

            document.body.addEventListener("click", (e) => {
                if (e.target.getAttribute("id") !== "search-options") {
                    dropdown.classList.remove("show");
                    searchOptions.classList.add("d-none");
                }
            });
        }
    }
}
</script>
<style>
    .dropdown-menu-lg {
        width: 89%;
    }
</style>