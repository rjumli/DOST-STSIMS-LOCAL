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
                <button @click="cstm()" v-b-tooltip.hover title="Custom list of subjects" class="me-1 btn btn-outline-primary btn-icon waves-effect waves-light" type="button">
                    <div class="btn-content"><i class="ri-menu-add-fill"></i></div>
                </button>
                <button @click="add1()" v-b-tooltip.hover title="Add Subject" class="me-1 btn btn-outline-danger btn-icon waves-effect waves-light" type="button">
                    <div class="btn-content"><i class="ri-add-circle-fill"></i></div>
                </button>
                <button @click="create()" class="btn btn-success btn-label" type="button">
                    <div class="btn-content"><i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Submit </div>
                </button>
            </div>
        </div>
    </b-row>
    <hr class="text-muted"/>
    <table class="table table-bordered mb-0">
        <tbody class="text-center font-size-11">
            <tr class="align-middle" v-for="list in lists" v-bind:key="list.id" style="line-height: 10px;">
                <td @click="openSwitch(list)" style="cursor: pointer; width: 7%;">
                    <i v-if="list.is_lab == true" class='bx bx bxs-flask h5 mb-n2 text-warning'></i>
                    <i v-else class='bx bxs-book-open  h5 mb-n2 text-info'></i>
                </td>
                <td class="fw-bold fs-12">{{ list.code }} <span v-if="list.is_lab == true" class="text-warning fw-bold">(Lab)</span></td>
                <td>{{ list.subject }} <span v-if="list.is_lab == true" class="text-warning fw-bold">(Lab)</span></td>
                <td style="width: 9.5%;">{{ list.unit }}</td>
            </tr>
        </tbody>
    </table>
    <Enrollment ref="enrollment"/>
    <Switch ref="switch" @update="handleUpdate"/>
 </template>
 <script>
 import Switch from './Modals/Switch.vue';
 import Enrollment from './Modals/Enrollment.vue';
 export default {
    components: { Enrollment, Switch },
    data(){
        return {
            selected: { semester: { semester: {}}, level: {}},
            info: {},
            prospectus: {},
            lists: [],
            subjects: [],
            customs: [],
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
        create(){
            let data = new FormData();
            data.append('id', (this.info.id != undefined) ? this.info.id : '');
            data.append('lists', (this.lists.length != 0) ? JSON.stringify(this.lists) : '');
            data.append('file_type', 'enrollment');
            data.append('type', 'enrollment');
            this.$refs.enrollment.set(data);
        },
        openSwitch(subject){
            this.$refs.switch.set(this.subjects,subject,this.selected.scholar_id);
        },
        handleUpdate(val){
            this.set(val,this.selected);
        }
    }
 }
 </script>