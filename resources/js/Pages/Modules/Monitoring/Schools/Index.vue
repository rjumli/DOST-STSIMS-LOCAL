<template>
    <Head title="Monitoring Schools" />
    <PageHeader :title="title" :items="items" />
     <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-sidebar">
            <div class="p-4 d-flex flex-column h-100 file-detail-content-scroll" data-simplebar>
                {{selected}}
            </div>
         </div>
        <div class="file-manager-content p-3 pb-0 w-100" ref="myDiv">
            <div class="row mt-0">
                <div class="col-md-12 mb-2">
                    <b-button variant="light" size="sm" class="w-lg waves-effect waves-light me-1">Mark All</b-button>
                    <b-button variant="light" size="sm" class="w-lg waves-effect waves-light">Select All Close</b-button>
                    <b-button variant="light" size="sm" class="w-lg waves-effect waves-light">Select All Open</b-button>
                </div>
                <div class="col-md-12 mt-1">
                    <div class="table-responsive mt-0">
                        <simplebar data-simplebar style="height: 62vh;">
                            <table class="table table-bordered table-nowrap align-middle">
                                <thead class="table-light fs-12 thead-fixed">
                                <tr>
                                    <th width="5%" class="text-center">
                                        <input class="form-check-input fs-16" v-model="mark" type="checkbox" value="option" />
                                    </th>
                                    <th width="37%">School</th>
                                    <th width="15%" class="text-center">Semester</th>
                                    <th width="15%" class="text-center">Start At</th>
                                    <th width="15%" class="text-center">End At</th>
                                    <th width="10%" class="text-center">Status</th>
                                </tr>
                            </thead>
                                <tbody class=" fs-12">
                                    <tr v-for="(list,index) in schools.data" v-bind:key="index">
                                        <td class="text-center">
                                            <input type="checkbox" v-model="list.selected" class="form-check-input" />
                                        </td>
                                        <td>{{list.name}}</td>
                                        <td class="text-center">{{list.academic_year}}</td>
                                        <td class="text-center">{{list.start}}</td>
                                        <td class="text-center">{{list.end}}</td>
                                        <td class="text-center">
                                            <b-badge v-if="list.status" variant="success">Open</b-badge>
                                            <b-badge v-else variant="danger">Close</b-badge>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </simplebar>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
<script>
import simplebar from 'simplebar-vue';
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    components: { PageHeader, simplebar },
    props: ['schools','settings'],
    data() {
        return {
            currentUrl: window.location.origin,
            title: "SCHOOL MONITORING",
            items: [{text: "Monitoring", href: "/",},{text: "Schools",active: true,},],
            mark: false,
            selected: []
        };
    },
    watch: {
        mark(){
            if(this.mark){
                this.schools.data.forEach(item => {
                    item.selected = true;
                    this.selected.push(item.id);
                });
            }else{
                this.schools.data.forEach(item => {
                    item.selected = false;
                });
                this.selected = [];
            }
        },
        'schools.data': {
            deep: true,
            handler() {
                this.selected = this.schools.data
                    .filter(item => item.selected)
                    .map(selectedItem => selectedItem.id);
            }
        }
    },
    methods: {
        selectAllWithStatus() {
        this.selected = this.schools.data
            .filter(item => item.status)
            .map(selectedItem => selectedItem.id);
        }
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
