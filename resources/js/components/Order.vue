<template>
    <div>
        <div class="row justify-content-center" v-if="orders.length > 0" >
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3" v-for="(order, index) in orders" :key="index">
                <div class="card">
                    <div class="card-header">Order # {{ order.id }}</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ order.table.table_number }}</h5>
                        <p class="card-text" v-for="(item, z) in order.order_items" :key="z">
                            {{item.qty}} x {{item.products.product_name}}
                        </p>
                        <button class="btn btn-xs btn-success" @click="serve(order.id)">Serve <i class="fas fa-plus-circle"></i></button>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted"> @ {{ order.order_date | moment("from", "now") }} by {{ order.user.name }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="text-center">
            <div class="col-md-4 mx-auto pt-5">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>No order for now <router-link to="/dinein" class="btn btn-success">Go to dine in</router-link></strong> 
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                orders: []
            }
        },
        computed: {
        },
        methods: {
            serve(order_id) {

                Swal.fire({
                    title: 'Please confirm',
                    text: "Are you sure?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Im sure!'
                }).then((result) => {
                    if (result.value) {
                        axios.get('/api/order/serve/' + order_id)
                        .then(res => {
                            this.loadOrders();
                        })
                        .catch(error => {
                            console.log(error);
                        });
                    }
                })

                
            },
            loadOrders() {
                this.$Progress.start();
                axios.get('/api/order/get')
                .then(res => {
                    this.orders = res.data;
                    this.$Progress.finish();
                })
                .catch(error => {
                    console.log(error);
                    this.$Progress.fail();
                });
            }
        },
        created() {
            this.loadOrders();
        },
        mounted() {
            console.log('Component mounted.')
        }
    }

</script>
