<template>
    <b-modal v-model="showModal" :title="(type) ? type : 'Update'" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform mb-2">
            <div class="row g-2">
                <div class="col-md-12">
                    
                    <table class="table table-centered table-bordered table-nowrap mb-2">
                        <tbody class="fs-11">
                            <tr>
                                <td class="text-center text-muted fs-12" v-if="selected.length > 0">
                                    <b class="text-primary">{{selected.length}} scholars</b> have been selected to update their {{(type == 'Update Level') ? 'level' : 'status' }}.
                                </td>
                                <td class="text-center text-muted fs-12" v-else>
                                    Currently, there are no selected scholars for updating.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <Multiselect class="form-control"
                    placeholder="Select Action"
                    v-model="type" :close-on-select="true" 
                    :searchable="false" :canClear="false"
                    :options="['Update Status','Update Level']"/>
                </div>
                <div class="col-md-12 mb-3 mt-n2" v-if="selected.length >0">
                    <hr class="text-muted"/>
                    <div class="alert alert-warning mb-xl-0 mt-n2" role="alert">Please double-check all fields before saving.</div>
                </div>
                <div class="col-md-12 mt-n2" v-if="type == 'Update Status' && selected.length >0">
                    <Multiselect class="form-control"
                    placeholder="Select Status" label="name" trackBy="name"
                    v-model="status" :close-on-select="true" :canClear="false"
                    :searchable="false" :groups="true"
                    :options="status_list"/>
                </div>
                <div class="col-md-12 mt-n2" v-if="type == 'Update Level' && selected.length >0">
                    <Multiselect class="form-control"
                    placeholder="Select Level" label="name" trackBy="name"
                    v-model="level" :close-on-select="true" :canClear="false"
                    :searchable="false"
                    :options="level_list"/>
                </div>
            </div>
        </b-form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button v-if="selected.length >0" @click="save('ok')" variant="primary" :disabled="form.processing" block>Save</b-button>
        </template>
    </b-modal>
</template>
<script>
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
export default {
    props: ['statuses','dropdowns'],
    components: { Multiselect },
    data(){
        return {
            currentUrl: window.location.origin,
            errors: [],
            selected: [],
            showModal: false,
            type: null,
            level: null,
            status: null,
            form: {}
        }
    },
    computed: {
        status_list : function() {
            return [{
                label: 'Ongoing',
                options: this.statuses.filter(x => x.type == 'Ongoing')
            },{
                label: 'Progress',
                options: this.statuses.filter(x => x.type == 'Progress')
            }];
        },
        level_list : function() {
           return this.dropdowns.filter(x => x.classification == 'Level');
        }
    },
    methods : {
        show(data){
            this.selected = data;
            this.showModal = true;
        },
        save(){
            if(this.type == 'Update Status'){
                this.form = this.$inertia.form({
                    scholars: this.selected,
                    status_id: this.status,
                    type: 'status'
                });
            }else{
                this.form = this.$inertia.form({
                    scholars: this.selected,
                    level_id: this.level,
                    type: 'level'
                });
            }

            this.form.post('/monitoring',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                    this.$emit('info',true);
                }
            });
        },
        hide(){
            this.type = null;
            this.selected = [];
            this.scholars = [];
            this.status = null;
            this.showModal = false;
        }
    }
}
</script>