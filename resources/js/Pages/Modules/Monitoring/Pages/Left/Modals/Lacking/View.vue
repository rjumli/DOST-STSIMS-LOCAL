<template>
    <b-modal v-model="showModal" :hide-footer="(viewSubject) ? false : true" title="View Profile" size="lg" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <template v-slot:header>
            <div style="border-bottom: 1px solid #ccc; width: 100%;">
                <i @click="showModal=false" class="ri-close-circle-fill float-end me-3" style="cursor:pointer; font-size: 30px;"></i>
                <b-row class="mt-n1" style="margin-bottom: 12px;">
                    <b-col md>
                        <b-row class="align-items-center g-1">
                            <b-col md="auto">
                                <b-img class="img-thumbnail rounded-circle" style="width: 50px; height: 50px;" alt="img" src="/imagess/avatars/avatar.jpg" data-holder-rendered="true"></b-img>
                            </b-col>
                            <b-col md>
                                <div class="ms-2 mt-n2">
                                    <h5 class="modal-title fs-15">{{scholar.firstname}} {{scholar.lastname}}</h5>
                                    <div class="hstack gap-3 flex-wrap mt-0 mb-n2">
                                        <div class="text-primary"><i class="ri-account-circle-fill align-bottom me-1"></i>
                                            <span class="text-body text-muted fs-12">{{scholar.education.school }}</span>
                                        </div>
                                        <div class="vr"></div>
                                        <div class="text-primary"><i class="ri-map-pin-2-fill align-bottom me-1"></i>
                                            <span class="text-body text-muted fs-12">
                                               {{scholar.education.course }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </b-col>
                        </b-row>
                    </b-col>
                </b-row>
            </div>
        </template>
        <div class="mb-3 mt-n1" v-if="!viewSubject">
            <table class="table table-bordered table-nowrap align-middle mb-0">
                <thead class="table-light">
                    <tr class="fs-11">
                        <th width="7%" class="text-center">#</th>
                        <th width="25%" class="text-center">Academic Year</th>
                        <th width="25%" class="text-center">Semester</th>
                        <th width="20%" class="text-center">Level</th>
                        <th width="20%" class="text-center">Total</th>
                        <th width="5%" class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(list,index) in scholar.enrollments" v-bind:key="list.id" class="fs-11">
                        <td class="text-center">{{index + 1}}</td>
                        <td class="text-center">
                            <h5 class="fs-12 mb-0 text-dark">{{ list.semester.academic_year }}</h5>
                        </td>
                        <td class="text-center">{{list.semester.semester.name}}</td>
                        <td class="text-center">{{list.level.others}}</td>
                        <td class="text-center">{{list.subjects.length}}</td>
                        <td class="text-center">
                            <b-button @click="viewLists(list)" variant="soft-primary" v-b-tooltip.hover title="View Enrollment" size="sm" class="edit-list"><i class="ri-eye-fill align-bottom"></i> </b-button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mb-1 mt-n1" v-else>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb p-3 py-2 bg-light mb-0">
                    <li class="breadcrumb-item fw-bold">{{selected.semester.academic_year}}</li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page"> {{selected.semester.semester.name}} </li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page"> {{selected.level.others}} </li>
                </ol>
            </nav>
            <hr class="text-muted mt-1 mb-2"/>
            <div class="table-responsive">
                <simplebar data-simplebar style="height: calc(100vh - 600px);">
                    <table class="table table-bordered table-nowrap align-middle mb-0">
                        <thead class="table-dark thead-fixed">
                            <tr class="fs-11">
                                <th width="7%" class="text-center">#</th>
                                <th width="15%" class="text-center">Code</th>
                                <th width="48%" class="text-center">Subject</th>
                                <th width="15%" class="text-center">Unit</th>
                                <th width="15%" class="text-center">Grade</th>
                                <!-- <th width="5%" class="text-center"></th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(list,index) in selected.subjects" v-bind:key="list.id" class="fs-11">
                                <td class="text-center">{{index + 1}}</td>
                                <td class="text-center">{{ list.code }}</td>
                                <td class="text-center">{{list.subject}}</td>
                                <td class="text-center">{{list.unit}}</td>
                                <td class="text-center">
                                    <select v-model="list.grade" class="form-select form-select-sm mt-n1 mb-n1">
                                        <option :value="null" disabled>-</option>
                                        <option :value="list1.grade" v-for="(list1,index) in scholar.education.gradings" v-bind:key="index">{{list1.grade}}</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </simplebar>
            </div>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="save()" variant="primary" :disabled="form.processing" block>Proceed</b-button>
        </template>
    </b-modal>
    <Grade ref="grade" @message="viewSubject = false"/>
</template>
<script>
import Grade from '../../../../../Enrollments/Pages/Modals/Grade.vue';
import simplebar from 'simplebar-vue';
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
export default {
    props: ['statuses'],
    components: { Multiselect, simplebar, Grade },
    data(){
        return {
            currentUrl: window.location.origin,
            scholar: { education: {}},
            selected: { semester:{ semester:{}}, subjects: [], level: {}},
            showModal: false,
            viewSubject: false,
            form: {}
        }
    },
    watch: {
        datares: {
            deep: true,
            handler(val = null) {
                if(this.showModal && val != null && val !== ''){
                    let id = val.id;
                    let index = this.scholar.enrollments.findIndex(item => item.id === id);
                    if(val.code == 'empty'){
                        this.scholar.enrollments.splice(index, 1);
                        this.showModal = false;
                    }else{
                        this.scholar.enrollments[index] = val;
                    }
                }
            },
        },
    },
    computed: {
        datares() {
            return this.$page.props.flash.data;
        },
    },
    methods:{
        show(data){
            this.scholar = data;
            this.showModal = true;
        },
        viewLists(lists){
            this.selected = lists;
            this.viewSubject = true;
        },
        save(){
            let data = new FormData();
            data.append('id',(this.selected != undefined) ? this.selected.id : '');
            data.append('lists',(this.selected.subjects.length != 0) ? JSON.stringify(this.selected.subjects) : '');
            data.append('file_type','grade');
            data.append('type','grade');
            data.append('subtype','monitoring');
            this.$refs.grade.set(data);
        }
    }
}
</script>
<style>
.modal-header {
    padding-top: 15px;
    padding-left: 0px;
    padding-right: 0px;
}
.form-floating > .form-control {
  height: 50px;
  min-height: 50px;
  line-height: 1;
  padding-top: 1.300rem;
  font-size: 12px;
}
</style>

<style>
.thead-fixed {
   position: sticky;
   top: 0;
   background-color: #fff; /* Set the background color for the fixed header */
   z-index: 1; /* Ensure the fixed header is above the scrolling content */
}
.tfoot-fixed {
   position: sticky;
   bottom: 0;
   background-color: #fff; /* Set the background color for the fixed header */
   z-index: 1; /* Ensure the fixed header is above the scrolling content */
}
</style>