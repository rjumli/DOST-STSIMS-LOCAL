<template>
    <b-row class="g-2 mb-2 mt-n2">
        <b-col lg>
            <div class="input-group mb-1">
                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                <input type="text" v-model="filter.keyword" placeholder="Search Qualifier" class="form-control" style="width: 40%;">
                <input type="text" v-model="filter.year" placeholder="Year Awarded" class="form-control" style="width: 30px;">
                <Multiselect class="form-control" style="width:12%"
                 placeholder="Select Status" label="name" trackBy="name"
                v-model="filter.status" :close-on-select="true" :canDeselect="false" :canClear="false"
                :searchable="false" :options="status_list" />
                <span @click="refresh" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;"> 
                    <i class="bx bx-refresh search-icon"></i>
                </span>
                <b-button type="button" variant="primary" @click="openFilter">
                    <i class="ri-filter-fill align-bottom me-1"></i> Filter
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
                    <th style="width: 15%;" class="text-center">Address</th>
                    <th style="width: 15%;" class="text-center">Program</th>
                    <th style="width: 15%;" class="text-center">Awarded Year</th>
                    <th style="width: 15%;" class="text-center">Type</th>
                    <th style="width: 15%;" class="text-center">Status</th>
                    <th style="width: 10%;"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="list in lists" v-bind:key="list.id" :class="[(list.is_active == 0) ? 'table-warnings' : '']">
                    <td>
                        <div class="avatar-xs" v-if="list.profile.avatar == 'n/a'">
                            <span class="avatar-title rounded-circle">{{list.profile.lastname.charAt(0)}}</span>
                        </div>
                        <div v-else>
                            <img class="rounded-circle avatar-xs" :src="currentUrl+'/imagess/avatars/'+list.profile.avatar" alt="">
                        </div>
                    </td>
                    <td>
                        <h5 class="fs-13 mb-0 text-dark">{{list.profile.lastname}}, {{list.profile.firstname}} {{list.profile.middlename[0]}}.</h5>
                        <p class="fs-11 text-muted mb-0">{{list.spas_id }}</p>
                    </td>
                    <!-- <td class="text-center fs-12">
                        {{list.address.hs_school}}
                    </td> -->
                        <td class="text-center">
                        <h5 class="fs-11 mb-0 text-dark">{{list.address.name}}</h5>
                        <p class="fs-11 text-muted mb-0">
                            {{(list.address.province) ? list.address.province.name+',' : ''}}
                            {{(list.address.region) ? list.address.region.region : ''}}
                        </p>
                    </td>
                    <td class="text-center">
                        <h5 class="fs-12 mb-0 text-dark">{{list.program.name}}</h5>
                        <p class="fs-11 text-muted mb-0">{{list.subprogram.name }}</p>
                    </td>
                    <td class="text-center">{{list.qualified_year}}</td>
                        <td class="text-center">
                        <span :class="'badge '+list.type.color+' '+list.type.others">{{list.type.name}}</span>
                    </td>
                    <td class="text-center">
                        <span :class="'badge '+list.status.color+' '+list.status.others">{{list.status.name}}</span>
                    </td>
                    <td class="text-end">
                        <b-button variant="soft-primary" v-if="list.type.name != 'Enrolled'" @click="openEndorse(list,'endorse',index)" v-b-tooltip.hover title="Endorse" size="sm" class="edit-list me-1"><i class="ri-swap-fill align-bottom"></i> </b-button>
                        <b-button v-if="list.type.name != 'Enrolled'"  @click="openAdd(list,'add',index)" variant="soft-primary" v-b-tooltip.hover title="Add Scholar" size="sm" class="edit-list me-1"><i class="ri-user-add-fill align-bottom"></i> </b-button>
                        <b-button v-if="list.address.is_completed == 0" @click="openUpdate(list,'update',index)" variant="soft-danger" v-b-tooltip.hover title="Update Address" size="sm" class="remove-list me-1"><i class="ri-map-pin-fill align-bottom"></i></b-button>
                        <b-button variant="soft-primary" @click="openEdit(list,'edit',index)" v-b-tooltip.hover title="Edit" size="sm" class="edit-list"><i class="ri-pencil-fill align-bottom"></i> </b-button>
                    </td>
                </tr>
            </tbody>
        </table>
        <Pagination class="ms-2 me-2" v-if="meta" @fetch="extractPageNumber" :lists="lists.length" :links="links" :pagination="meta" />
    </div>
    <Add @status="fetch()" ref="add"/>
    <Endorse @status="fetch()" ref="endorse"/>
    <Filter :regions="regions" :dropdowns="dropdowns" :programs="program_list" :subprograms="subprogram_list" @status="subfilter" ref="filter"/>
    <Update @status="fetch()" ref="update"/>
    <Edit @status="fetch()" :statuses="status_list" ref="edit"/>
</template>
<script>
import Add from './Modals/Add.vue';
import Edit from './Modals/Edit.vue';
import Endorse from './Modals/Endorse.vue';
import Filter from './Modals/Filter.vue';
import Update from './Modals/Update.vue';
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    props: ['regions','dropdowns','program_list','subprogram_list','status_list'],
    components : { Pagination, Filter, Multiselect, Add, Endorse, Update, Edit },
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
                status: 14,
                sort: 'asc'
            },
            subfilters: [],
            flag: '',
            index: '',
            pageNo: null,
        }
    },
    watch: {
        "filter.keyword"(newVal){
            this.checkSearchStr(newVal)
        },
        "filter.year"(newVal){
            this.checkSearchStr(newVal)
        },
        "filter.status"(){
            this.fetch();
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
                'status': (this.filter.status === '' || this.filter.status == null) ? '' : this.filter.status,
                'year': (this.filter.year === '' || this.filter.year == null) ? '' : this.filter.year,
                'counts': parseInt(((window.innerHeight-350)/56)),
                'sort': this.filter.sort
            };

            info = (Object.keys(info).length == 0) ? '-' : JSON.stringify(info);

            page_url = page_url || '/scholars/qualifiers';
            axios.get(page_url, {
                params: {
                    info: info,
                    subfilters: this.subfilters,
                    option: 'lists',
                    page: this.pageNo
                }
            })
            .then(response => {
                this.lists = response.data.data;
                this.meta = response.data.meta;
                this.links = response.data.links;
            })
            .catch(err => console.log(err));
        },
        openFilter(){
            this.$refs.filter.show();
        },
        openAdd(data,type,index){
            this.index = index;
            this.flag = type;
            this.$refs.add.show(data);
        },
        openEndorse(data,type,index){
            this.index = index;
            this.flag = type;
            this.$refs.endorse.show(data);
        },
        openUpdate(data,type,index){
            this.index = index;
            this.flag = type;
            this.$refs.update.show(data,type);
        },
        openEdit(data,type,index){
            this.index = index;
            this.flag = type;
            this.$refs.edit.show(data,type);
        },
        subfilter(list){
            this.subfilters = list;
            this.subfilters = (Object.keys(this.subfilters).length == 0) ? '-' : JSON.stringify(this.subfilters);
            this.fetch();
        },
        extractPageNumber(page_url) {
            this.pageNo = null;
            const nextUrl = page_url;
            const regex = /page=(\d+)/;
            const match = nextUrl.match(regex);

            if (match) {
                this.pageNo = match[1];
            } else {
                this.pageNo = null;
            }
            this.fetch();
        },
    }
}
</script>