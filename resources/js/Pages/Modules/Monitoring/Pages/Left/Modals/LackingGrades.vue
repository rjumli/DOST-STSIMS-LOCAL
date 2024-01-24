<template>
    <b-modal v-model="showModal" title="Lacking Grades" hide-footer style="--vz-modal-width: 900px;" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered>    

        <input class="form-control mb-3" v-model="filter.keyword" type="text" placeholder="Search Scholar">
        <hr class="text-muted"/>
        <div class="table-responsive">
            <table class="table table-bordered table-nowrap align-middle mb-0">
                <thead class="table-light">
                    <tr class="fs-11">
                        <th width="7%" class="text-center">#</th>
                        <th width="30%">Name</th>
                        <th width="30%" class="text-center">No. of enrollments</th>
                        <th width="27%" class="text-center">No. of subjects</th>
                        <th width="5%" class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(list,index) in lists" v-bind:key="list.id" class="fs-11">
                            <td class="text-center">{{index + 1}}</td>
                        <td>
                            <h5 class="fs-12 mb-0 text-dark">{{ list.firstname }} {{ list.lastname }}</h5>
                            <p class="text-muted fs-11 mb-n1">{{ list.spas_id }}</p>
                        </td>
                        <td class="text-center">{{list.enrollments.length}}</td>
                        <td class="text-center">{{calculateTotalSum(list)}}</td>
                        <td class="text-center">
                            <b-button @click="openView(list)" variant="soft-primary" v-b-tooltip.hover title="View Enrollment" size="sm" class="edit-list me-1"><i class="ri-eye-fill align-bottom"></i> </b-button>
                            <b-button @click="openView(list)" variant="soft-danger" v-b-tooltip.hover title="Notify" size="sm" class="edit-list"><i class="ri-alarm-warning-fill align-bottom"></i> </b-button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
        </div>
    </b-modal>
    <View ref="view"/>
</template>
<script>
import View from './Lacking/View.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination, View },
    data() {
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            filter: {
                keyword: null
            },
            showModal: false
        }
    },
    watch: {
        keyword(newVal){
            this.checkSearchStr(newVal)
        }
    },
    methods: {
        show() {
            this.fetch();
            this.showModal = true;
        },
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 500),
        fetch(page_url){
            page_url = page_url || '/monitoring';
            axios.get(page_url, {
                params: {option: 'lacking_grades', keyword: this.filter.keyword}
            })
            .then(response => {
                this.lists = response.data.data;
                this.meta = response.data.meta;
                this.links = response.data.links;
            })
            .catch(err => console.log(err));
        },
        openView(data){
            this.$refs.view.show(data);
        },
        calculateTotalSum(list) {
            return list.enrollments.reduce((total, enrollment) => total + enrollment.subjects.length, 0);
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>
