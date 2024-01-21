<template>
    <b-row class="mt-n2 mb-3">
        <div class="col-md-6">
            <div class="page-title-left mt-2">
                <ol class="breadcrumb m-0 fs-15">
                    <li class="breadcrumb-item fw-bold">{{ selected.semester.academic_year }}</li>
                    <li class="breadcrumb-item fw-semibold">
                        <span class="text-success">{{ selected.level.others }}</span>
                    </li>
                    <li class="breadcrumb-item fw-semibold">
                        <span class="text-success">{{ selected.semester.semester.name }}</span>
                    </li>
                </ol>
            </div> 
        </div>
        <div class="col-md-6">
            <div class="hstack float-end  mt-4 mt-sm-0">
                <button @click="openCustom()" v-b-tooltip.hover title="Custom list of subjects" class="me-1 btn btn-outline-primary btn-icon waves-effect waves-light" type="button">
                    <div class="btn-content"><i class="ri-menu-add-fill"></i></div>
                </button>
                <button @click="openRetake()" v-b-tooltip.hover title="Add Subject" class="me-1 btn btn-outline-danger btn-icon waves-effect waves-light" type="button">
                    <div class="btn-content"><i class="ri-add-circle-fill"></i></div>
                </button>
                <button @click="openSubmit()" class="btn btn-success btn-label" type="button">
                    <div class="btn-content"><i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Submit </div>
                </button>
            </div>
        </div>
    </b-row>
    <hr class="text-muted"/>
    <div class="table-responsive">
      <simplebar data-simplebar style="height: calc(100vh - 300px);">
         <table class="table table-centered table-bordered table-nowrap mb-0">
            <thead class="table-light thead-fixed">
               <tr class="font-size-11">
                  <th style="width: 5%;" class="text-center"></th>
                  <th style="width: 15%;" class="text-center">Code</th>
                  <th style="width: 75%;" class="text-center">Subject</th>
                  <th style="width: 5%;" class="text-center">Unit</th>
               </tr>
            </thead>
            <tbody class=" fs-12">
                <tr class="align-middle" v-for="list in lists" v-bind:key="list.id" style="line-height: 10px;">
                    <td class="text-center" @click="openSwitch(list)" style="cursor: pointer;">
                        <i v-if="list.is_lab == true" class='bx bx bxs-flask h5 mb-n2 text-warning'></i>
                        <i v-else class='bx bxs-book-open  h5 mb-n2 text-info'></i>
                    </td>
                    <td class="fw-semibold fs-12 text-center">{{ list.code }} <span v-if="list.is_lab == true" class="text-warning fw-semibold">(Lab)</span></td>
                    <td class="text-center">{{ list.subject }} <span v-if="list.is_lab == true" class="text-warning fw-semibold">(Lab)</span></td>
                    <td class="text-center">{{ list.unit }}</td>
                </tr>
            </tbody>
            <tfoot class="table-light tfoot-fixed">
               <tr class="font-size-12">
                    <th colspan="3"></th>
                    <th class="text-center text-primary">{{units}}</th>
               </tr>
            </tfoot>
         </table>
      </simplebar>
   </div>
    <Enrollment ref="enrollment"/>
    <Switch ref="switch" @update="handleUpdate"/>
    <Retake ref="retake"/>
 </template>
 <script>
 import Retake from './Modals/Retake.vue';
 import Switch from './Modals/Switch.vue';
 import Enrollment from './Modals/Enrollment.vue';
 import simplebar from 'simplebar-vue';
 export default {
    components: { Enrollment, Switch, Retake, simplebar },
    data(){
        return {
            selected: { semester: { semester: {}}, level: {}},
            info: {},
            prospectus: {},
            lists: [],
            subjects: [],
            customs: [],
            custom: false
        }
    },
    computed: {
        units: function () {
            var sum = 0;
            if(this.lists != undefined){
                this.lists.forEach(e => {
                sum += Number(e.unit);
                });
            }
            return sum
        }
    },
    methods: {
        set(info,data){
            this.info = info;
            this.selected = data;

            let semesters,semester, prospectus;

            prospectus = info.prospectus;
            semesters = prospectus.filter(x => x.year_level === this.selected.level.others);
            semester = semesters[0].semesters.filter(x => x.semester === this.selected.semester.semester.name);

            this.prospectus = Object.assign({},semester[0]);
            this.lists = this.prospectus.courses;

            prospectus.forEach((item) => {
                item.semesters.forEach((it) => {
                    it.courses.forEach((i) => {
                        if(!i.hasOwnProperty("grade")){
                            this.subjects.push(i);
                        }
                        this.customs.push(i);
                    });
                });
            });
        },
        openSubmit(){
            let data = new FormData();
            data.append('id',this.selected.id);
            data.append('lists', (this.lists.length != 0) ? JSON.stringify(this.lists) : '');
            data.append('file_type', 'enrollment');
            data.append('type', 'enrollment');
            this.$refs.enrollment.set(data,this.selected.semester.academic_year,this.selected.level.others,this.selected.semester.semester.name);
        },
        openSwitch(subject){
            this.$refs.switch.set(this.subjects,subject,this.selected.scholar_id);
        },
        openRetake(){
            this.$refs.retake.set(this.customs);
        },
        openCustom(){
            this.custom = true;
            this.lists = [];
            this.$refs.custom.set(this.customs);
        },
        handleUpdate(val){
            this.set(val,this.selected);
        }
    }
 }
 </script>