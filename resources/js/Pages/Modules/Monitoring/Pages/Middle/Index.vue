<template>
    <div class="col-lg">
        <form class="d-none d-md-block">
            <div class="input-group mb-1">
                <span class="input-group-text"><i class="ri-search-line search-icon"></i></span>
                <input type="text" placeholder="Search Scholar" class="form-control" style="width: 30%;" id="search-options">
                <div class="dropdown-menu dropdown-menu-lg" id="search-dropdown" style="top: 38px; width: 100%;">
                    <div class="notification-list">
                        <b-link  @click="showScholar(list)" v-for="(list, index) of names" :key="index" class="d-flex dropdown-item notify-item py-2">
                            <img :src="currentUrl+'/images/avatars/'+list.profile.avatar" class="me-3 rounded-circle avatar-xs" alt="user-pic" />
                            <div class="flex-1">
                                <h6 class="m-0">{{ list.profile.name}}</h6>
                                <span class="fs-11 mb-0 text-muted">{{list.program}}</span>
                            </div>
                        </b-link>
                    </div>
                </div>
                <button class="btn btn-primary btn-md" type="button">
                    <div class="btn-content"><i class="ri-search-eye-line align-bottom me-1"></i> Search </div>
                </button>
            </div>
        </form>
    </div>
    <hr class="text-muted"/>
    <div class="row mt-0 mb-4" style="--vz-gutter-x: 0.6rem;">    
        <div class="col-sm-12 col-lg-12 mb-2" style="cursor: default;">
            <div class="p-2 border border-dashed rounded">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm me-2" @click="updateAcademic()" style="cursor: pointer;">
                        <div class="avatar-title rounded bg-transparent text-primary fs-24"><i
                                class="ri-calendar-fill"></i></div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted fs-11 mb-0">Academic Year :</p>
                        <h5 class="fs-16 text-success mb-0">{{settings.data.academic_year}}</h5>
                    </div>
                    <div class="text-end">
                        <b-button @click="openMonitor()" variant="light" class="btn-label waves-effect waves-light"><i class="bx bxs-webcam label-icon align-middle fs-16 me-2"></i> Monitor Schools</b-button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div @click="updateSemester('Semester',terms.semester)" class="p-2 border border-dashed rounded" :style="'cursor:'+ (terms.semester > 0 ? 'pointer' : 'default')">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm me-2">
                        <div class="avatar-title rounded bg-transparent text-primary fs-24">
                            <i class="ri-calendar-2-line"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted fs-11 mb-1">Semester :</p>
                        <h5 class="fs-13 mb-0">{{(settings.data.semester) ? settings.data.semester.name : 'Not Available'}}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div @click="updateSemester('Trimester',terms.trimester)" class="p-2 border border-dashed rounded" :style="'cursor:'+ (terms.trimester > 0 ? 'pointer' : 'default')">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm me-2">
                        <div class="avatar-title rounded bg-transparent text-primary fs-24"><i
                                class="ri-calendar-2-line"></i></div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted fs-11 mb-1">Trimester :</p>
                       <h5 class="fs-13 mb-0">{{(settings.data.trimester) ? settings.data.trimester.name : 'Not Available'}}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div @click="updateSemester('Quarter Term',terms.quarter)" class="p-2 border border-dashed rounded" :style="'cursor:'+ (terms.quarter > 0 ? 'pointer' : 'default')">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm me-2">
                        <div class="avatar-title rounded bg-transparent text-primary fs-24"><i class="ri-calendar-2-line"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted fs-11 mb-1">Quarter Term :</p>
                        <h5 class="fs-13 mb-0">{{(settings.data.quarter) ? settings.data.quarter.name : 'Not Available'}}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="text-muted mt-n2"/>
    <Link :href="`/monitoring/schools`">
        <b-button variant="light" class="btn-label waves-effect waves-light"><i class="bx bxs-webcam label-icon align-middle fs-16 me-2"></i> School Monitoring</b-button>
    </Link>
    <Link :href="`/monitoring/batches`" class="ms-2">
        <b-button variant="light" class="btn-label waves-effect waves-light"><i class="bx bxs-webcam label-icon align-middle fs-16 me-2"></i> Batch Monitoring  </b-button>
    </Link>
     <Link :href="`/monitoring/schools`" class="ms-2">
        <b-button variant="light" class="btn-label waves-effect waves-light"><i class="bx bxs-webcam label-icon align-middle fs-16 me-2"></i> School Monitoring</b-button>
    </Link>
    
    
    <Year ref="year"/>
    <Monitor ref="monitor"/>
    <Semester ref="semester" :dropdowns="dropdowns"/>
</template>
<script>
import Year from './Modals/Year.vue';
import Monitor from './Modals/Monitor.vue';
import Semester from './Modals/Semester.vue';
export default {
    components: { Year, Semester, Monitor },
    props: ['settings','terms','dropdowns'],
    methods: {
        updateAcademic(){
            this.$refs.year.show();
        },
        updateSemester(type,count){
            if(count > 0){
                this.$refs.semester.show(type);
            }
        },
        openMonitor(){
            this.$refs.monitor.show(this.settings);
        },
    }
}
</script>