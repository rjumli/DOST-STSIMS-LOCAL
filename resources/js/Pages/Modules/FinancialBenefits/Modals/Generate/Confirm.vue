<template>
     <b-modal v-model="showModal" title="Confirm Release" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform mb-2">
            <div class="row g-1">
                <div class="col-lg-12">
                    <div class="form-floating">
                        <input type="text" :value="' RELEASE NO.'+(selected.count+1)+' FOR THE MONTH OF '+selected.month.toUpperCase()" class="form-control" readonly>
                        <label>Release no.</label>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-floating">
                        <input type="text" :value="selected.pending.length" class="form-control" readonly>
                        <label>No. of Scholars</label>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-floating">
                        <input type="text" :value="formatMoney(total)" class="form-control" readonly>
                        <label>Total</label>
                    </div>
                </div>
                <div class="col-lg-12 mt-n2 mb-n1">
                    <hr class="text-muted"/>
                </div>
                <div class="col-md-12 mt-0">
                    <div class="form-check">
                        <input class="form-check-input" v-model="dv" type="checkbox" id="gridCheck"/>
                        <label class="form-check-label" for="gridCheck">Do you want to add Disbursement Voucher?</label>
                    </div>
                    <input v-if="dv" type="text" class="form-control mt-2" placeholder="Disbursement Voucher" v-model="dv_no"/>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="alert alert-warning mb-xl-0 fs-11" role="alert"><b>Please check the scholar's account number to ensure that it is updated</b></div>
                </div>
            </div>
        </b-form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary" :disabled="form.processing" block>Confirm</b-button>
        </template>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            showModal: false,
            dv: false,
            dv_no: '',
            selected: {month:'', scholars:{data:[]}, pending:[]},
            total: '',
            lists: [],
            form: {}
        }
    },
    methods: {
        set(lists,data,total){
            this.lists = lists;
            this.selected = data;
            this.total = total;
            this.showModal = true;
        },
        create(){
            this.form = this.$inertia.form({
                'lists': this.lists,
                'total': this.total,
                'dv': this.dv,
                'dv_no': this.dv_no,
                'number': this.selected.count+1,
                'type': 'pending'
            })
            this.form.post('/financial-benefits',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.showModal = false;
                }
            });
        },
        formatMoney: function formatMoney(value) {
            var val = (value / 1).toFixed(2).replace(',', '.');
            return '₱' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        hide() {
            this.showModal = false;
        }
    }
}
</script>
<style>
.form-floating > .form-control {
  height: 50px;
  min-height: 50px;
  line-height: 1;
  padding-top: 1.300rem;
  font-size: 12px;
}
</style>