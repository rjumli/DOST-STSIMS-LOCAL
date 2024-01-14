<template>
    <b-modal v-model="showModal" title="Lock Prospectus" :size="(!prospectus.is_locked) ? 'xl' : ''" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <div class="row">
            <div class="col-md-12 mt-2 mb-n3" v-if="!prospectus.is_locked">
                <div class="alert alert-danger alert-dismissible alert-label-icon rounded-label" role="alert"><i class="ri-error-warning-line label-icon"></i>You won't be able to update the prospectus anymore, so please double-check every piece of information, as there is no turning back. </div>
                <div class="alert alert-warning alert-dismissible alert-label-icon rounded-label mt-n2" role="alert"><i class="ri-error-warning-line label-icon"></i>Only one prospectus can be active at a time, and only a locked prospectus can be marked as active.</div>
            </div>
            <div class="col-md-12 mt-2 mb-n3" v-else>
                <div class="alert alert-warning alert-dismissible alert-label-icon rounded-label" role="alert"><i class="ri-error-warning-line label-icon"></i>You will be able to update the prospectus again! </div>
            </div>
        </div>
        <div class="row mt-4" v-if="!prospectus.is_locked">
            <div class="col-md-12" style="height: 400px; overflow: auto;">
                <div class="table-responsive" :class="(index == 0) ? 'mt-0' : 'mt-3'" v-for="(year,index) in subjects" v-bind:key="index">
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
                                        <td width="15%" class="text-center fs-11">{{course.code}}</td>
                                        <td width="70%" class="text-center fs-11">{{course.subject}}</td>
                                        <td class="text-center fs-11" width="15%">
                                            {{ course.unit}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <template v-slot:footer>
            <b-button @click="showModal = false" variant="light" block>Cancel</b-button>
            <b-button @click="save('ok')" variant="primary"  block>Update</b-button>
        </template>
    </b-modal>
</template>

<script>
export default {
    props: ['lists'],
    data() {
        return {
            currentUrl: window.location.origin,
            showModal: false,
            prospectus: '',
            subjects: []
        }
    },
    methods: {
        set(data){
            this.subjects = JSON.parse(data.subjects);
            this.prospectus = data;
            this.showModal = true;
        },
        save(){
            this.form = this.$inertia.form({
                id: this.prospectus.id,
                is_locked: (this.prospectus.is_locked) ? 0 : 1,
                type: 'lock'
            })

            this.form.put('/schools/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.showModal = false;
                }
            });
        }
    }
}
</script>
