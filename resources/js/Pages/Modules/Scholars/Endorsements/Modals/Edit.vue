<template>
    <b-modal v-model="showModal" title="Update Qualifier" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered>    
        <ul class="list-unstyled mb-0 vstack gap-3" v-if="user">
            <li>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img :src="currentUrl+'/imagess/avatars/'+user.profile.avatar" alt="" class="avatar-sm rounded">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-14 mb-1 text-primary">{{user.profile.name}}</h6>
                        <span :class="'badge '+user.status.color+' '+user.status.others">{{user.status.name}}</span>
                    </div>
                </div>
            </li>
            <hr class="text-muted mt-0" />
        </ul>

        <b-form class="customform mt-2">
            <div class="row customerform">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Status: <span v-if="errors.status_type" v-text="errors.status_type" class="haveerror"></span></label>
                        <Multiselect class="form-control"
                        placeholder="Select Status" label="name" trackBy="name"
                        v-model="status" :close-on-select="true" 
                        :searchable="true" :options="status_list" />
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                   <div class="form-group">
                        <label>Reason: <span v-if="errors.reason" v-text="errors.reason" class="haveerror"></span></label>
                        <textarea v-model="reason" class="form-control" maxlength="225" rows="1" placeholder="Reason"></textarea>
                    </div>
                </div>
            </div>
        </b-form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary" :disabled="form.processing" block>Save</b-button>
        </template>
    </b-modal>
</template>
<script>
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
export default {
    components: { Multiselect },
    props: ['dropdowns','statuses'],
    data(){
        return {
            currentUrl: window.location.origin,
            errors: [],
            user: {
                status: {},
                address: {
                    info: {}
                },
                profile: {},
            },
            status: '',
            reason: '',
            form : {},
            showModal: false,
        }
    },
     computed: {
        status_list : function() {
            return this.statuses.filter(x => x.name != 'Waiting').filter(x => x.name != 'Enrolled');
        }
    },
    methods : {
        show(data){
            this.user = data;
            this.errors = [];
            this.showModal = true;
        },
        hide(){
            this.showModal = false;
        },
        create(){
            let data = new FormData();

            data.append('id', this.user.id);
            data.append('type', 'edit');
            data.append('status_type', this.status);
            data.append('reason', this.reason);

            this.$inertia.post('/scholars/qualifiers',data,{
                preserveScroll: true,
                forceFormData: true,
                onSuccess: (response) => {
                    this.clear();
                },
                onError: (response) =>{
                    this.errors.push(response);
                }
            });
        },
        clear(){
            this.$emit('status',true);
            this.status = '';
            this.showModal = false;
        }
    }
}
</script>
