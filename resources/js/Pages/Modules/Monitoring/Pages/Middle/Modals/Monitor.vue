<template>
    <b-modal v-model="showModal" title="School Semesters" hide-footer header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop fullscreen>    
        <div class="row mt-3">
   
            <div class="col-md-12">
                <div class="table-responsive mt-2">
                    <table class="table table-nowrap align-middle">
                        <thead class="table-light fs-12">
                            <tr>
                                <th width="3%" class="text-center">
                                    <input class="form-check-input fs-16" type="checkbox" value="option" />
                                </th>
                                <th width="35%">School</th>
                                <th width="17%" class="text-center">Semester</th>
                                <th width="15%" class="text-center">Start At</th>
                                <th width="15%" class="text-center">End At</th>
                                <th width="10%" class="text-center">Status</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="table-responsive mt-n3" data-simplebar style="height: 62vh; overflow: auto;">
                    <table class="table table-bordered table-nowrap align-middle">
                        <tbody>
                            <tr v-for="(list,index) in schools" v-bind:key="index">
                                <td width="3%" class="text-center">
                                    <input type="checkbox" name="chk_child" class="form-check-input" />
                                </td>
                                <td width="35%" class="fw-semibold">{{list.name}}</td>
                                <td width="17%" class="text-center">{{list.academic_year}}</td>
                                <td width="15%" class="text-center">{{list.start}}</td>
                                <td width="15%" class="text-center">{{list.end}}</td>
                                <td width="10%" class="text-center">
                                    <b-badge v-if="list.status" variant="success">Open</b-badge>
                                    <b-badge v-else variant="danger">Close</b-badge>
                                </td>
                                <td width="5%"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary" :disabled="final != key" block>Save</b-button>
        </template> -->
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            showModal: false,
            settings: '',
            schools: []
        }
    },
    methods : {
        show(settings) {
            this.settings = settings;
            this.showModal = true;
            this.fetch();
        },
        fetch(){
            axios.get(this.currentUrl+'/monitoring', {
                params: {
                    type: 'schoolsemesters'
                }
            })
            .then(response => {
                this.schools = response.data.data;
            })
            .catch(err => console.log(err));
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>

