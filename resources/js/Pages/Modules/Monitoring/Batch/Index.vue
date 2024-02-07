<template>
    <Head title="Monitoring Schools" />
    <PageHeader :title="title" :items="items" />
     <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-sidebar">
            <div class="p-4 d-flex flex-column h-100" style="overflow: auto;">
                <b-row class="align-items-center">
                    <b-col cols="12">
                        <Multiselect class="form-control"
                        placeholder="Select Year"
                        v-model="year" :close-on-select="true" :canClear="false"
                        :searchable="false" :options="years" />
                        <hr class="text-muted"/>
                    </b-col>
                    <b-col cols="6" class="mt-2">
                        <h6 class="text-primary text-uppercase fw-semibold text-truncate fs-12 mb-3">{{year}} Ongoing Scholars</h6>
                        <h4 class="fs-24 mb-0">{{subtotal}}</h4>
                        <p class="mb-0 mt-2 text-muted">out of {{total}} scholars</p>
                    </b-col>
                    <b-col cols="6">
                        <div class="text-center">
                            <img src="/imagess/illustrator-1.png" class="img-fluid" alt="">
                        </div>
                    </b-col>
                    <b-col cols="12">
                        <div class="mt-2 pt-2">
                            <b-progress class="progress-lg rounded-pill" :max="subtotal">
                                <b-progress-bar  v-for="(list,index) in modifiedItems" v-bind:key="index" :value="list.scholars_count" :variant="list.color" class="rounded-0" />
                            </b-progress>
                        </div>
                    </b-col>
                    <b-col cols="12">
                        <div class="mt-2 pt-2">
                            <div class="d-flex mb-2" v-for="(list,index) in modifiedItems" v-bind:key="index">
                                <div class="flex-grow-1">
                                    <p class="text-truncate text-muted fs-14 mb-0">
                                        <i class="mdi mdi-circle align-middle me-2" :class="'text-'+list.color"></i>{{list.name}}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <p class="mb-0">{{list.scholars_count}}</p>
                                </div>
                            </div>
                        </div>
                    </b-col>
                </b-row>
                <hr class="text-muted"/>
                <!-- <h6 class="fs-11 text-muted text-uppercase mb-3 mt-0">BATCH {{year}} SCHOLARS</h6>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="ri-database-2-line fs-17"></i>
                    </div>
                    <div class="flex-grow-1 ms-3 overflow-hidden">
                        <b-progress class="animated-progress progress-sm mb-2" :max="total">
                            <b-progress-bar :value="ongoing" variant="primary" />
                        </b-progress>
                        <span class="text-muted fs-12 d-block text-truncate"><b>{{ongoing}}</b> out of <b>{{total}}</b> ongoing scholars are enrolled.</span>
                    </div>
                </div>
                <hr class="text-muted"/> -->
            </div>
         </div>
        <div class="file-manager-content w-100 p-4 pb-0" ref="myDiv">
            <b-row class="g-2 mb-2 mt-n2">
                <div class="col-md-12 mb-2">
                    <div class="input-group mb-1">
                        <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                        <input type="text" v-model="searchTerm" @input="search" placeholder="Search Scholar" class="form-control" style="width: 60%;">
                        <b-button type="button" variant="primary" @click="openUpdate">
                            <i class="ri-add-circle-fill align-bottom me-1"></i>Update
                        </b-button>
                    </div>
                </div>
            </b-row>
            <div class="table-responsive mt-0">
                <simplebar data-simplebar style="height: 62vh;">
                    <table class="table table-nowrap align-middle">
                        <thead class="table-light fs-12 thead-fixed">
                        <tr>
                            <th width="5%" class="text-center">
                                <input class="form-check-input fs-12" v-model="mark" type="checkbox" value="option" />
                            </th>
                            <th width="25%">Name</th>
                            <th width="35%" class="text-center">School</th>
                            <th width="15%" class="text-center">Level</th>
                            <th width="15%" class="text-center">Status</th>
                            <th width="15%" class="text-center"></th>
                        </tr>
                    </thead>
                        <tbody class=" fs-12">
                            <tr v-for="(list,index) in lists" v-bind:key="index" :class="(index == matchedRowIndex || list.selected) ? 'table-warning' : ''" :id="'row-' + index">
                                <td class="text-center">
                                    <input type="checkbox" @change="toggleSelection(list.id)" v-model="list.selected" class="form-check-input" />
                                </td>
                                <td>
                                    <h5 class="fs-12 mb-0 mt-n3">{{list.name}}</h5>
                                    <p class="fs-11 text-muted mb-n3">{{list.spas_id}}</p>
                                </td>
                                <td class="text-center">
                                    <h5 class="fs-12 mb-0 mt-n3">{{list.school}}</h5>
                                    <p class="fs-11 text-muted mb-n3">{{list.course}}</p>
                                </td>
                                <td class="text-center">{{list.level}}</td>
                                <td class="text-center">
                                    <span :class="'badge '+list.status.color+' '+list.status.others">{{list.status.name}}</span>
                                </td>
                                <td class="text-center">
                                    <b-button @click="openView(list)" variant="soft-primary" v-b-tooltip.hover title="View" size="sm" class="edit-list"><i class="ri-eye-fill align-bottom"></i> </b-button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </simplebar>
            </div>
        </div>
    </div>
    <Action :statuses="dropdowns.statuses.data" :dropdowns="dropdowns.lists.data" @info="update()" ref="action"/>
</template>
<script>
import Action from './Modals/Action.vue';
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
import simplebar from 'simplebar-vue';
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    components: { PageHeader, simplebar, Multiselect, Action},
    props: ['years','dropdowns','statistics'],
    data() {
        return {
            currentUrl: window.location.origin,
            title: "BATCH MONITORING",
            items: [{text: "Monitoring", href: "/",},{text: "Batches",active: true,},],
            mark: false,
            lists: [],
            searchTerm: '',
            matchedRowIndex: null,
            selected: [],
            year: '',
            subtotal: 0,
            total: 0,
            ongoing: 0,
            statuses: []
        };
    },
    created(){
        this.year = this.statistics.year;
        this.subtotal = this.statistics.subtotal;
        this.total = this.statistics.total;
        this.ongoing = this.statistics.ongoing;
        this.statuses = this.statistics.statuses;
        this.fetch();
    },
    computed: {
        modifiedItems() {
            let a = (this.statuses) ? this.statuses.map(item => ({...item, color: item.color.replace('bg-', '')})) : [];
            return a;
        }
    },
    watch: {
        mark(){
            if(this.mark){
                this.lists.forEach(item => {
                    item.selected = true;
                    this.selected.push(item.id);
                });
            }else{
                this.lists.forEach(item => {
                    item.selected = false;
                });
                this.selected = [];
            }
        },
        year(){
            this.fetch();
        }
    },
    methods: {
        fetch(){
            axios.get(this.currentUrl+'/monitoring', {
                params: {
                    year: this.year,
                    option: 'batches',
                }
            })
            .then(response => {
                this.lists = response.data.data;
                this.year = response.data.updated.year;
                this.subtotal = response.data.updated.subtotal;
                this.total = response.data.updated.total;
                this.ongoing = response.data.updated.ongoing;
                this.statuses = response.data.updated.statuses;
            })
            .catch(err => console.log(err));
        },
        openUpdate(){
            this.$refs.action.show(this.selected);
        },
        toggleSelection(id) {
            const isSelected = this.selected.includes(id);
            if(!isSelected) {
                this.selected.push(id);
            }else{
                const index = this.selected.indexOf(id);
                this.selected.splice(index, 1);
            }
        },
        update(){
            this.selected = [];
            this.fetch();
        },
        search() {
            const searchTerm = this.searchTerm.toLowerCase();
            const matchedIndices = this.lists.reduce((indices, scholar, index) => {
                if (scholar.name.toLowerCase().includes(searchTerm)) {
                    indices.push(index);
                }
                return indices;
            }, []);
            if (matchedIndices.length > 0 && searchTerm !== '') {
                const closestIndex = matchedIndices.reduce((closest, currentIndex) => {
                    const closestDistance = Math.abs(closest - searchTerm.length);
                    const currentDistance = Math.abs(currentIndex - searchTerm.length);
                    return currentDistance < closestDistance ? currentIndex : closest;
                }, matchedIndices[0]);

                this.matchedRowIndex = closestIndex;
                const rowId = 'row-' + closestIndex;
                const matchedRow = document.getElementById(rowId);
                if(matchedRow){
                    matchedRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }else {
                this.matchedRowIndex = null;
            }
        },
    }
}
</script>
<style>
      .thead-fixed {
        position: sticky;
        top: 0;
        background-color: #fff; /* Set the background color for the fixed header */
        z-index: 1; /* Ensure the fixed header is above the scrolling content */
    }
</style>
<!-- 
                <table class="table table-centered table-bordered table-nowrap mb-0">
                    <thead class="table-light thead-fixed">
                        <tr class="text-muted fs-10">
                            <th width="20%" class="text-center">Awarded</th>
                            <th width="12%" style="cursor: pointer;" class="text-center" v-for="(list,index) in statistics.types" v-bind:key="index">{{list.name}}</th>
                            <th width="15%" class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody class="fs-11 ">
                        <tr v-for="(list,index) in statistics.statuses" v-bind:key="index">
                            <td class="text-center fw-medium" style="cursor:pointer;">{{list.status}}</td>
                            <td class="text-center" v-for="(count,index) in list.count" v-bind:key="index">{{count}} </td>
                            <td class="text-center fw-bold">{{list.total}}</td>
                        </tr>
                    </tbody>
                    <tfoot class="text-muted table-light fs-10 tfoot-fixed">
                        <tr>
                            <th class="text-center">Total</th>
                            <th class="text-center" v-for="(count,index) in statistics.totals.count" v-bind:key="index">{{count}}</th>
                            <th class="text-center">{{statistics.totals.total}}</th>
                        </tr>
                    </tfoot>
                </table> -->