<template>
    <Head title="Monitoring Schools" />
    <PageHeader :title="title" :items="items" />
     <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-sidebar">
            <div class="p-4 d-flex flex-column h-100 file-detail-content-scroll" data-simplebar>
                {{schools.data[0]}}
            </div>
         </div>
        <div class="file-manager-content p-3 pb-0 w-100" ref="myDiv">
            <div class="row mt-0">
                <div class="col-md-12 mb-2">
                    <span class="fs-14 fw-bold text-primary">SCHOOLS WITH ONGOING SCHOLARS</span>
                    <hr class="text-muted mb-2 mt-2"/>
                        <b-button  @click="selectClose()" :variant="closeVariant" size="sm" class="w-lg waves-effect waves-light me-1">Select All Close</b-button>
                        <b-button @click="selectOpen()" :variant="openVariant" size="sm" class="w-lg waves-effect waves-light me-1">Select All Open</b-button>
                        <b-button @click="selectPublic()" :variant="publicVariant" size="sm" class="w-lg waves-effect waves-light me-1">Select Public</b-button>
                        <b-button @click="selectPrivate()" :variant="privateVariant" size="sm" class="w-lg waves-effect waves-light">Select Private</b-button> 
                        <span class="float-end mt-1">{{selected.length}} schools are selected.</span>
                    <hr class="text-muted mb-n1 mt-2"/>
                </div>
                <div class="col-md-12 mt-1">
                    <div class="table-responsive mt-0">
                        <simplebar data-simplebar style="height: 62vh;">
                            <table class="table table-bordered table-nowrap align-middle">
                                <thead class="table-light fs-12 thead-fixed">
                                <tr>
                                    <th width="5%" class="text-center">
                                        <input class="form-check-input fs-16" v-model="mark" type="checkbox" value="option" />
                                    </th>
                                    <th width="37%">School</th>
                                    <th width="15%" class="text-center">Semester</th>
                                    <th width="15%" class="text-center">Start At</th>
                                    <th width="15%" class="text-center">End At</th>
                                    <th width="10%" class="text-center">Status</th>
                                </tr>
                            </thead>
                                <tbody class=" fs-12">
                                    <tr v-for="(list,index) in schools.data" v-bind:key="index">
                                        <td class="text-center">
                                            <input type="checkbox" v-model="list.selected" class="form-check-input" />
                                        </td>
                                        <td>{{list.name}}</td>
                                        <td class="text-center">{{list.semester}}</td>
                                        <td class="text-center">{{list.start}}</td>
                                        <td class="text-center">{{list.end}}</td>
                                        <td class="text-center">
                                            <b-badge v-if="list.status" variant="success">Open</b-badge>
                                            <b-badge v-else variant="danger">Close</b-badge>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </simplebar>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Action ref="action"/>
</template>
<script>
import Action from './Modals/Action.vue';
import simplebar from 'simplebar-vue';
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    components: { PageHeader, simplebar, Action },
    props: ['schools','settings'],
    data() {
        return {
            currentUrl: window.location.origin,
            title: "SCHOOL MONITORING",
            items: [{text: "Monitoring", href: "/",},{text: "Schools",active: true,},],
            mark: false,
            selected: [],
            closeVariant: 'light',
            openVariant: 'light',
            publicVariant: 'light',
            privateVariant: 'light',
        };
    },
    watch: {
        mark(){
            if(this.mark){
                this.schools.data.forEach(item => {
                    item.selected = true;
                    this.selected.push(item.id);
                });
            }else{
                this.schools.data.forEach(item => {
                    item.selected = false;
                });
                this.selected = [];
            }
            this.resetOtherVariants('');
        },
        'schools.data': {
            deep: true,
            handler() {
                this.selected = this.schools.data
                .filter(item => item.selected)
                .map(selectedItem => selectedItem.id);
            }
        }
    },
    methods: {
        selectClose() {
            this.schools.data.forEach(school => {
                school.selected = school.status === false;
            });
            this.closeVariant = 'primary';
            this.resetOtherVariants('closeVariant');
            this.openAction();
        },
        selectOpen() {
            this.schools.data.forEach(school => {
                school.selected = school.status === true;
            });
            this.openVariant = 'primary';
            this.resetOtherVariants('openVariant');
            this.openAction();
        },
        selectPublic() {
            this.schools.data.forEach(school => {
                school.selected = school.class == 'Public';
            });
            this.publicVariant = 'primary';
            this.resetOtherVariants('publicVariant');
            this.openAction();
        },
        selectPrivate() {
            this.schools.data.forEach(school => {
                school.selected = school.class == 'Private';
            });
            this.privateVariant = 'primary';
            this.resetOtherVariants('privateVariant');
            this.openAction();
        },
        resetOtherVariants(clickedVariant) {
            ['closeVariant', 'openVariant', 'publicVariant', 'privateVariant'].forEach(variant => {
                if (variant !== clickedVariant) {
                this[variant] = 'light';
                }
            });
        },
        openAction(){
            this.$refs.action.show(this.selected,this.schools.data,this.settings);
        }
    }
}
</script>
<style>
      .thead-fixed {
        position: sticky;
        top: 0;
        background-color: #fff; /* Set the background color for the fixed header */
        z-index: 1; /* Ensure the fixed header is above the scrolling content */
    }
</style>
