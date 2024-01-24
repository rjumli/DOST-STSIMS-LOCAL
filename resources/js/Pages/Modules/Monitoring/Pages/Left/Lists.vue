<template>
    <ul class="list-unstyled vstack gap-3" style="cursor:pointer;">
        <li>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-xs">
                        <div class="avatar-title rounded bg-primary-subtle text-primary">
                        <i class="bx bxs-graduation fs-17"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="mb-0 fs-13">Graduating Scholars</h5>
                    <p class="mb-0 fs-12 text-muted">Qualified for graduation</p>
                </div>
                <!-- <div class="avatar-group">
                    <div class="avatar-group-item" v-for="user in scholarsGraduating" v-bind:key="user.id">
                        <a class="d-inline-block" v-b-tooltip.hover :title="user.firstname+' '+user.lastname">
                            <img :src="currentUrl+'/images/avatars/'+user.avatar" alt="" class="rounded-circle avatar-xxs">
                        </a>
                    </div>
                    <div class="avatar-group-item" v-if="counts.graduating.length > 0"> 
                        <a class="" href="javascript: void(0);" target="_self" v-b-tooltip.hover :title="counts.graduating.length +' more scholars'">
                            <div class="avatar-xxs">
                                <span class="avatar-title rounded-circle bg-info text-white">+</span>
                            </div>
                        </a>
                    </div>
                </div> -->
            </div>
        </li> 
        <li @click="openLacking()">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-xs">
                        <div class="avatar-title rounded bg-secondary-subtle text-secondary">
                        <i class="ri-file-text-line fs-17"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="mb-0 fs-13">Lacking Grades</h5>
                    <p class="mb-0 fs-12 text-muted">No Grades in an inactive semester</p>
                </div>
                <div class="avatar-group">
                    <div class="avatar-group-item" v-if="lists.lacking_grades > 0">
                        <div class="avatar-xxs">
                            <span class="avatar-title rounded-circle bg-light fs-11 text-primary fw-bold">{{lists.lacking_grades}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-xs">
                        <div class="avatar-title rounded bg-success-subtle text-success fw-bold">
                        <i class="ri-wallet-line fs-17"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="mb-0 fs-13">Unreleased Benefits</h5>
                    <p class="mb-0 fs-12 text-muted">Stipend not released</p>
                </div>
                <div class="avatar-group">
                    <div class="avatar-group-item" v-if="lists.unreleased_benefits > 0">
                        <div class="avatar-xxs">
                            <span class="avatar-title rounded-circle bg-light fs-11 text-primary fw-bold">{{lists.unreleased_benefits}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-xs">
                        <div class="avatar-title rounded bg-warning-subtle text-warning">
                        <i class="ri-alert-fill fs-17"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="mb-0 fs-13">Missed Enrollment</h5>
                    <p class="mb-0 fs-12 text-muted">No COR submitted</p>
                </div>
                <div class="avatar-group">
                    <div class="avatar-group-item" v-if="lists.missed_enrollments > 0">
                        <div class="avatar-xxs">
                            <span class="avatar-title rounded-circle bg-light fs-11 text-primary fw-bold">{{lists.missed_enrollments}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-xs">
                        <div class="avatar-title rounded bg-info-subtle text-info">
                        <i class="ri-question-fill fs-17"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="mb-0 fs-13">Academic Deficiency</h5>
                    <p class="mb-0 fs-12 text-muted">Scholars with academic deficiencies</p>
                </div>
                <div class="avatar-group">
                    <div class="avatar-group-item" v-if="lists.terminations > 0">
                        <div class="avatar-xxs">
                            <span class="avatar-title rounded-circle bg-light fs-11 text-primary fw-bold">{{lists.terminations}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-xs">
                        <div class="avatar-title rounded bg-danger-subtle text-danger">
                        <i class="ri-error-warning-line fs-17"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="mb-0 fs-13">For Termination</h5>
                    <p class="mb-0 fs-12 text-muted">2 Grades Failed in a semester</p>
                </div>
                <div class="avatar-group">
                    <div class="avatar-group-item" v-if="lists.terminations > 0">
                        <div class="avatar-xxs">
                            <span class="avatar-title rounded-circle bg-light fs-11 text-primary fw-bold">{{lists.terminations}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
    <LackingGrades ref="lacking_grades"/>
</template>
<script>
import LackingGrades from './Modals/LackingGrades.vue';
export default {
    components: { LackingGrades },
    props: ['lists'],
    data(){
        return {
            currentUrl: window.location.origin
        }
    },
    methods: {
        openLacking(){
            this.$refs.lacking_grades.show();
        }
    }
}
</script>