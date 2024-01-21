<template>
    <div class="p-4 d-flex flex-column h-100" style="overflow: auto;">
        <Search @set="handleSetEvent" @show="showProspectus"/>
        <div class="alert alert-secondary mt-n2" v-if="!scholar" role="alert">Search scholar to enroll. Using name or SPAS Id</div>
        <ul class="list-unstyled mb-0 vstack gap-3" v-else>
            <li>
                <hr class="mt-0" />
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img :src="currentUrl+'/imagess/avatars/'+scholar.profile.avatar" alt="" class="avatar-sm rounded">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-14 mb-1 text-primary">{{scholar.profile.name}}</h6>
                        <span :class="'badge bg-secondary bg-'+scholar.status.color">{{scholar.status.name}}</span>
                    </div>
                </div>
            </li>
            <li>
                <i @click="showProspectus()" style="font-size: 70px; top: 130px; right: 15px; position: absolute; cursor: pointer;" class="ri-todo-fill text-light"></i>
                <i class="mdi mdi-seal-variant me-2 align-middle text-primary fs-16"></i><span class="fs-12">{{scholar.program.name}}</span>
            </li>
            <li class="mt-n3"><i class="ri-building-line me-2 align-middle text-primary fs-16"></i><span class="fs-12">{{scholar.education.school.name}}</span></li>
            <li class="mt-n3"><i class="mdi mdi-school-outline me-2 align-middle text-primary fs-16"></i><span class="fs-12">{{scholar.education.course.name}}</span></li>
            <li v-if="scholar.education.school.semester">
                <div v-if="!scholar.education.school.is_enrolled" class="alert alert-warning alert-dismissible alert-label-icon rounded-label" role="alert">
                    <i class="ri-alert-line label-icon"></i>Scholar is not Enrolled
                </div>
                <div v-else class="alert alert-secondary alert-dismissible alert-label-icon rounded-label" role="alert">
                    <i class="ri-check-double-line label-icon"></i>Scholar is currently enrolled 
                </div>
            </li>
            <li v-else>
                <div class="alert alert-danger alert-dismissible alert-label-icon rounded-label" v-b-tooltip.hover title="Please remind the coordinator" role="alert">
                    <i class="ri-error-warning-line label-icon"></i>Enrollment is still closed.
                </div>
            </li>
            <li>
                <div data-simplebar style="height: calc(100vh - 530px);" class="mt-n2">
                    <ul class="list-group mb-1">
                        <li class="list-group-item" v-for="list in scholar.enrollments" v-bind:key="list.id">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <i v-if="list.is_locked" v-b-tooltip.hover title="Enrollment already locked." class="ri-lock-2-fill float-end fs-24 text-light align-middle me-2"></i>
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 avatar-xs">
                                            <div v-if="list.is_enrolled" class="avatar-title rounded" :class="(list.is_grades_completed) ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning'">
                                                <i v-if="list.is_locked" class="ri-file-lock-fill"></i>
                                                <i v-else class="ri-pages-fill"></i>
                                            </div>
                                             <div v-else class="avatar-title bg-danger-subtle text-danger rounded">
                                                <i class="ri-file-warning-fill"></i>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 ms-2" @click="(list.is_enrolled) ? showAssessment(list) : showEnrollment(list)" style="cursor: pointer;">
                                            <h6 class="fs-13 mb-0"> {{ list.semester.academic_year }}
                                                <span class="text-muted"> / </span>
                                                <span class="text-info">{{ list.level.others }}</span>
                                                <span class="text-muted"> / </span>
                                                <span class="text-warning">{{ list.semester.semester.name }}</span></h6>
                                            <small class="text-muted">{{ list.created_at}}</small>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</template>
<script>
import Search from './Search.vue';
export default {
    components: { Search },
    data(){
        return {
            currentUrl: window.location.origin,
            show: null,
            scholar: null
        }
    },
    computed: {
        data() {
            return this.$page.props.flash;
        },
    },
    watch: {
        data: {
            deep: true,
            handler(val = null) {
                if(val != null && val !== ''){
                    switch(val.type){
                        case 'prospectus':
                            this.scholar.education = val.data.data;
                            this.showProspectus();
                        break;
                        case 'switch':
                            this.scholar.education = val.data.data;
                        break;
                        case 'enrollment':
                            (val.data.data) ? this.scholar.education.school.is_enrolled = true : '';
                            this.updateEnrollment(val.data.data);
                        break;
                        case 'grade':
                            this.updateEnrollment(val.data.data);
                        break;
                        case 'lock':
                            this.updateEnrollment(val.data.data);
                        break;
                    }
                    this.id = null;
                }
            },
        },
    },
    methods : {
        handleSetEvent(dataFromChild) {
            this.scholar = dataFromChild;
        },
        showProspectus(){
            this.$parent.showProspectus(this.scholar.education);
        },
        showEnrollment(data){
            this.$parent.showEnrollment(this.scholar.education.info,data);
        },
        showAssessment(data){
            this.$parent.showAssessment(data,this.scholar.education.school.gradings);
        },
        updateEnrollment(data){
            let id = data.id;
            let index = this.scholar.enrollments.findIndex(item => item.id === id);
            this.scholar.enrollments[index] = data;
            this.showAssessment(data);
        }
    }
}
</script>