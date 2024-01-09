<template>
    <b-row class="g-2 mb-2 mt-n2">
        <b-col lg>
            <div class="input-group mb-1">
                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                <input type="text" v-model="filter.keyword" placeholder="Search Scholar" class="form-control" style="width: 50%;">
                <!-- <select v-model="filter.program" @change="fetch()" class="form-select" id="inputGroupSelect01" style="width: 120px;">
                    <option :value="null" selected>Select Program</option>
                    <option :value="list.id" v-for="list in program_list" v-bind:key="list.id">{{list.name}}</option>
                </select> -->
                <!-- <select v-model="filter.type" @change="fetch()" class="form-select" id="inputGroupSelect01" style="width: 120px;">
                    <option :value="null" selected>Select Type</option>
                    <option value="1">Undergraduate</option>
                    <option value="0">JLSS</option>
                </select> -->
                <!-- <select v-model="filter.status" @change="fetch()" class="form-select" id="inputGroupSelect02" style="width: 120px;">
                    <option :value="null" selected>Select Status</option>
                    <option :value="list.id" v-for="list in status_list" v-bind:key="list.id">{{list.name}}</option>
                </select> -->
                <input type="text" v-model="filter.year" placeholder="Year Awarded" class="form-control" style="width: 30px;">
                <span @click="refresh" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;"> 
                    <i class="bx bx-refresh search-icon"></i>
                </span>
                <span @click="openFilter" class="input-group-text" v-b-tooltip.hover title="Filter" style="cursor: pointer;"> 
                    <i class="ri-filter-fill search-icon"></i>
                </span>
                 <span @click="openExport" class="input-group-text" v-b-tooltip.hover title="Export" style="cursor: pointer;"> 
                    <i class="ri-file-excel-2-fill search-icon"></i>
                </span>
                <b-button type="button" variant="primary" @click="openCreate">
                    <i class="ri-add-circle-fill align-bottom me-1"></i> Create
                </b-button>
            </div>
        </b-col>
    </b-row>
    <div class="table-responsive">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="table-light">
                <tr class="fs-11">
                    <th></th>
                    <th style="width: 30%;">Name</th>
                    <th style="width: 15%;" class="text-center">Program</th>
                    <th style="width: 15%;" class="text-center">Awarded Year</th>
                    <th style="width: 15%;" class="text-center">School</th>
                    <th style="width: 15%;" class="text-center">Status</th>
                    <th style="width: 10%;"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(user,index) in lists" v-bind:key="index" :class="[(user.is_completed == 0) ? 'table-warnings' : '']">
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 chat-user-img online user-own-img align-self-center me-n1 ms-n2">
                                <img :src="currentUrl+'/imagess/avatars/'+user.profile.avatar" class="rounded-circle avatar-xs" alt="">
                                <span class="user-status" :style="(user.profile.sex == 'M') ? 'background-color: #5cb0e5;' : 'background-color: #e55c7f;'"></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <h5 class="fs-12 mb-0 fw-semibold">{{user.profile.name}}</h5>
                        <p class="fs-11 text-muted mb-0">{{(user.spas_id) ? user.spas_id : user.stsims_id}} / {{user.profile.account_no}}</p>
                    </td>
                    <td class="fs-12 text-center">{{user.program}}</td>
                    <td class="fs-12 text-center">{{user.awarded_year}}</td>
                    <td class="text-center">
                        <p class="fs-12 mb-n1 text-dark">{{(user.education.school instanceof Object) ? user.education.school.name : user.education.school}}</p>
                        <p class="fs-12 text-muted mb-0">{{(user.education.course instanceof Object) ? user.education.course.name : user.education.course}}</p>
                    </td>
                    <td class="text-center">
                        <span :class="'badge '+user.status.color+' '+user.status.others">{{user.status.name}}</span>
                    </td>
                    <td class="text-end">
                        <!-- <b-button v-if="user.user == null" @click="authenticate(user)" variant="soft-primary" v-b-tooltip.hover title="Create Scholar Account" size="sm" class="edit-list me-1"><i class="ri-user-add-fill align-bottom"></i> </b-button> -->
                        <b-button @click="openUpdate(user,'status',index)" variant="soft-warning" v-b-tooltip.hover title="Update Status" size="sm" class="remove-list me-1"><i class="ri-pencil-fill align-bottom"></i></b-button>
                        <b-button @click="openView(user)" variant="soft-info" v-b-tooltip.hover title="View Profile" size="sm" class="remove-list me-n2"><i class="ri-eye-fill align-bottom"></i></b-button>
                        <!-- <b-button v-if="user.account_no == null && user.status.type == 'Ongoing'" @click="showUpdate(user,'scholar',index)" variant="soft-danger" v-b-tooltip.hover title="Update Account No." size="sm" class="remove-list me-1"><i class="ri-bank-card-2-fill align-bottom"></i></b-button>
                        <b-button v-if="user.education.is_completed == 0" @click="showUpdate(user,'education',index)" variant="soft-danger" v-b-tooltip.hover title="Update Education" size="sm" class="remove-list me-1"><i class="ri-hotel-fill align-bottom"></i></b-button>
                        <b-button v-if="user.addresses[0].is_completed == 0" @click="showUpdate(user,'address',index)" variant="soft-danger" v-b-tooltip.hover title="Update Address" size="sm" class="remove-list me-1"><i class="ri-map-pin-fill align-bottom"></i></b-button>
                        <Link v-if="user.is_completed == 1" :href="`/scholars/${user.code}`"><b-button variant="soft-info" v-b-tooltip.hover title="View" size="sm" class="remove-list me-1"><i class="ri-eye-fill align-bottom"></i></b-button></Link> -->
                        <!-- <b-button variant="soft-primary" v-b-tooltip.hover title="Edit" size="sm" class="edit-list"><i class="ri-pencil-fill align-bottom"></i> </b-button> -->
                    </td>
                </tr>
            </tbody>
        </table>
        <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
    </div>
    <View ref="view"/>
    <Create :regions="regions" :statuses="statuses" :dropdowns="dropdowns" :program_list="program_list" :subprogram_list="subprogram_list" ref="create"/>
    <Update :statuses="statuses" ref="update"/>
    <Filter :regions="regions" :dropdowns="dropdowns" :programs="program_list" :subprograms="subprogram_list" @status="subfilter" ref="filter"/>
</template>
<script>
import View from './Modals/Buttons/View.vue';
import Update from './Modals/Buttons/Update.vue';
import Create from './Modals/Buttons/Create.vue';
import Filter from './Modals/Buttons/Filter.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    props: ['regions','dropdowns','program_list','subprogram_list','statuses'],
    components : { Pagination, Create, Update, View, Filter },
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            filter: {
                subfilters: [],
                year: null,
                keyword: null,
                sort: 'asc'
            },
            subfilters: [],
            flag: '',
            index: ''
        }
    },
    watch: {
        "filter.keyword"(newVal){
            this.checkSearchStr(newVal)
        },
        "filter.year"(newVal){
            this.checkSearchStr(newVal)
        },
    },
    created(){
        this.fetch();
    },
    methods: {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        fetch(page_url) {
            let info = {
                'keyword': this.filter.keyword,
                'year': (this.filter.year === '' || this.filter.year == null) ? '' : this.filter.year,
                'counts': parseInt(((window.innerHeight-350)/56)),
                'sort': this.filter.sort
            };

            info = (Object.keys(info).length == 0) ? '-' : JSON.stringify(info);

            page_url = page_url || '/scholars/listing';
            axios.get(page_url, {
                params: {
                    info: info,
                    subfilters: this.subfilters,
                    option: 'lists',
                }
            })
            .then(response => {
                this.lists = response.data.data;
                this.meta = response.data.meta;
                this.links = response.data.links;
            })
            .catch(err => console.log(err));
        },
        openView(data){
            this.$refs.view.show(data);
        },
        openCreate(){
            this.$refs.create.show();
        },
        openUpdate(data,type,index){
            this.flag = type;
            this.index = index;
            this.$refs.update.set(data,type);
        },
        openFilter(){
            this.$refs.filter.show();
        },
        openExport(){},
        subfilter(list){
            this.subfilters = list;
            this.subfilters = (Object.keys(this.subfilters).length == 0) ? '-' : JSON.stringify(this.subfilters);
            this.fetch();
        },
    }
}
</script>