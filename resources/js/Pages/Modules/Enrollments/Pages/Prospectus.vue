<template>
    <div class="row mt-0">
        <div class="col-md-12 mb-2">
            <span class="fs-14 fw-bold text-primary">STUDENT PROSPECTUS</span>
            <hr class="text-muted mb-2 mt-2"/>
            <div class="todo-content position-relative px-4 mx-n4" id="todo-content">
                <div class="col-md-12" :style="{ height: (height) + 'px' }" style="overflow: auto;">
                    <div class="table-responsive" :class="(index == 0) ? 'mt-0' : 'mt-3'" v-for="(year,index) in prospectus" v-bind:key="index">
                        <table class="table table-bordered mb-0"> 
                            <thead>
                                <tr class="text-light text-center font-weight-bold font-size-11 bg-dark">
                                    <th>
                                        {{ year.year_level }}
                                    </th>
                                </tr>
                            </thead>
                        </table>
                        <div class="row g-0">
                            <div :class="(semester.semester == 'Summer Class') ? 'col-md-12' : 'col-md-6'" v-for="(semester,index) in year.semesters" v-bind:key="index">
                                <table class="table table-bordered mb-0" v-if="semester.courses.length > 0"> 
                                    <thead>
                                        <tr class="text-dark text-center font-weight-bold font-size-11" style="background-color: #ededed;">
                                            <th colspan="3">
                                                {{ semester.semester }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="font-size-11" v-for="(course,index) in semester.courses" v-bind:key="index">
                                            <td width="15%" class="text-center fw-bold fs-11">{{course.code}}</td>
                                            <td width="70%" class="text-center text-muted fs-11">{{course.subject}}</td>
                                            <td class="text-center fw-bold fs-11" width="15%" v-if="!course.hasOwnProperty('grades')">
                                                {{ course.grade}}
                                            </td>
                                            <td class="text-center fw-bold fs-11" width="15%" v-else>
                                                <span :class="(grade > 3 || grade == 'F') ? 'text-danger' : 'text-dark'" v-for="(grade,index) in course.grades" v-bind:key="index">
                                                    <span class="text-muted" v-if="course.grades.length > 1 && index != 0"> / </span> {{grade}} 
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import simplebar from 'simplebar-vue';
export default {
    components: { simplebar },
    data(){
        return {
            currentUrl: window.location.origin,
            selected: '',
            active: '',
            prospectus: ''
        }
    },
    methods:{
        set(data){
            this.selected = data;
            this.prospectus = data.info.prospectus;
            this.fetchActive();
        },
        fetchActive(){
            axios.get(this.currentUrl+'/enrollments', {
                params: {
                    option: 'activeprospectus',
                    school_course_id: this.selected.subcourse.id
                }
            })
            .then(response => {
                this.active = response.data;
                if(this.active.id != this.selected.info.id){
                    this.$refs.prospectus.show(this.active,this.selected.id,this.selected.subcourse.id);
                }
            })
            .catch(err => console.log(err));
        }
    }
}
</script>