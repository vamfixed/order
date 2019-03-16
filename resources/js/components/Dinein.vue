<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-4" v-for="table in tables" :key="table.id">
                <div class="card" :class="table.available == '1' ? 'bg-primary' : ' bg-danger'">
                    <div class="card-header">{{ table.table_number }}</div>
                    <div class="card-body">
                        <h5 class="card-title">Capacity: {{ table.capacity }}</h5>
                        <p class="card-text">Status: {{ table.status }} {{ table.order_status == null ? "" : "(" + table.order_status + ")" }}</p>
                        <p class="card-text">Available? {{ table.available == "1" ? "YES" : "NO" }}</p>
                        <button class="btn btn-xs btn-success" @click="pay(table)" v-if="table.available == '0' && table.order_status == 'SERVED'">Pay
                            <i class="fas fa-money-bill-alt"></i>
                        </button>
                        <button class="btn btn-xs btn-secondary" @click="dineIn(table)" v-if="table.available == '1'">Dine In
                            <i class="fas fa-plus-circle"></i>
                        </button>
                        <button class="btn btn-xs btn-info" v-if="table.available == '0'" @click="showOrder(table)">View Order
                            <i class="fas fa-folder-open"></i>
                        </button>
                    </div>
                    <div class="card-footer">
                        Net Pay <span class="float-right">{{ table.available == '1' ? '-' : table.total_amount }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalPayment" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Payment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <table class="table table-borderless">
                                <tr>
                                    <td>Total Amount</td>
                                    <td class="text-right" v-text="payment.total_amount">0.00</td>
                                </tr>
                                <tr>
                                    <td>Cash</td>
                                    <td><input type="number" @keyup="updateChange()" class="form-control text-right" v-model="payment.cash"></td>
                                </tr>
                                <tr>
                                    <td>Change</td>
                                    <td class="text-right" v-text="payment.change">0.00</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="savePayment()">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalOrder" tabindex="-1" role="dialog" aria-labelledby="modalOrder" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Orders on {{ vorderTable }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-hover table-border">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-right">Qty</th>
                                    <th class="text-right">Price</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="orderItem in vorderItems" :key="orderItem.id">
                                    <td>{{ orderItem.products.product_name }}</td>
                                    <td class="text-right">{{ orderItem.qty }}</td>
                                    <td class="text-right">{{ orderItem.rate }}</td>
                                    <td class="text-right">{{ orderItem.amount }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3">Total <b>{{ vorderItems.length }} item(s)</b></td>
                                    <td colspan="3" class="text-right">{{ netpay2 }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade bd-example-modal-lg" id="modalDineIn" tabindex="-1" role="dialog" aria-labelledby="modalDineInLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDineInLabel">Dine In for {{ dineInTable ?
                            dineInTable.table_number : "" }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <!-- <div class="col-md-12">
                                <div class="form-group">
                                    <label>Product</label>
                                    <select data-placeholder="Select a product" @change="updateOrderItem()" v-model="orderItem.product_id" name="product_id" id="product_id" width="100%" class="form-control">
                                        <option v-for="product in products" v-bind:value="product.id" v-bind:key="product.id">
                                            {{ product.product_name + ": " + product.product_description }}
                                        </option>
                                    </select>
                                </div>
                            </div> -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Product</label>
                                    <v-select label="product_name" v-model="selected" :options="products" @change="updateOrderItem()">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="number" readonly class="form-control" v-model="orderItem.price">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Quantity</label>
                                    <input type="number" @change="orderItem.total = orderItem.price * orderItem.quantity" class="form-control" v-model="orderItem.quantity">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Total</label>
                                    <input type="number" readonly class="form-control" v-model="orderItem.total">
                                </div>
                            </div>

                            <div class="col-md-12">
                                    <button class="btn btn-success btn-xs float-right" @click="addItemOrder">
                                        <i class="fas fa-plus-circle"></i>
                                    </button>
                            </div>
                        </div>


                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>PID</th>
                                    <th>Product Name</th>
                                    <th class="text-right">Price</th>
                                    <th class="text-right">Quantity</th>
                                    <th class="text-right">Total</th>
                                    <th class="text-center">CTRL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(orderI, index) in orderItems" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ orderI.product_id }}</td>
                                    <td>{{ orderI.product_name }}</td>
                                    <td class="text-right">{{ orderI.price }}</td>
                                    <td class="text-right">{{ orderI.quantity }}</td>
                                    <td class="text-right">{{ orderI.total }}</td>
                                    <td style="text-align: center;">
                                        <button class="btn btn-xs btn-danger" @click="removeOrderItem(orderI)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3">Total <b>{{ orderItems.length }} item(s)</b></td>
                                    <td colspan="3" class="text-right">{{ netpay }}</td>
                                    <td style="text-align: center;" @click="clearOrderItem">
                                        <button class="btn btn-xs btn-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                            <i class="fas fa-trash"></i>
                        </button>
                        <button type="button" @click="saveDineIn()" class="btn btn-primary">Save
                            <i class="fas fa-save"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                selected: null,
                orderItem: {
                    product_id: null,
                    product_name: null,
                    price: null,
                    quantity: null,
                    total: null
                },
                vorderItems: [],
                vorderTable: '',
                orderItems: [],
                dineInTable: null,
                tables: [],
                products: [],
                payment: {
                    table_id: 0,
                    order_id: 0,
                    total_amount: 0,
                    cash: 0,
                    change: 0,
                }
            }
        },
        computed: {
            netpay() {
                var total = 0;

                this.orderItems.forEach( x => {
                    total += parseFloat(x.total);
                });
                return total.toFixed(2);
            },

            netpay2() {
                var total = 0;

                this.vorderItems.forEach( x => {
                    total += parseFloat(x.amount);
                });
                return total.toFixed(2);
            }
        },
        methods: {
            savePayment() {
                if (this.payment.change < 0) {
                    this.ToastMessage("Please check the amount!", "error");
                    return;
                }

                // SAVE
                this.$Progress.start();
                axios.post('/api/dinein/payment', this.payment)
                .then(res => {
                    this.$Progress.finish();
                    $('#modalPayment').modal('hide');
                    this.displayTables();
                })
                .catch(error => {
                    this.$Progress.fail();
                    this.ToastMessage('Error: ' + error.message, 'error');
                });
            },
            showConfirmMessage(callback) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to do this?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Im sure!'
                }).then((result) => {
                    if (result.value) {
                        callback();
                    }
                })
            },
            ToastMessage(msg, type) {
                Toast.fire({
                    type: type,
                    title: msg
                })
            },

            dineIn(table) {
                $("#modalDineIn").modal('show');
                this.dineInTable = table;
            },
            pay(table) {
                $('#modalPayment').modal('show');
                this.payment.table_id = table.id;
                this.payment.order_id = table.order_id;
                this.payment.cash = 0;
                this.payment.total_amount = table.total_amount;
                this.updateChange();
                
            },
            addItemOrder() {
                this.orderItems.push(Object.assign({}, this.orderItem));
            },
            updateChange() {
                this.payment.change = (this.payment.cash - this.payment.total_amount).toFixed(2);
            },
            updateOrderItem() {
                // var values = this.products.map(function(o) { return o.id });
                // var index = values.indexOf(this.orderItem.product_id); 
                // var itemSelected = this.products[index];
                var itemSelected = this.selected;
                this.orderItem.product_id = itemSelected.id;
                this.orderItem.product_name = itemSelected.product_name;
                this.orderItem.price = itemSelected.price;
                this.orderItem.quantity = 1;
                this.orderItem.total = itemSelected.price * this.orderItem.quantity;
            },
            saveDineIn() {
                this.showConfirmMessage(() => {
                    axios.post('/api/dinein/save', { 
                        table: this.dineInTable, 
                        items: this.orderItems 
                    })
                    .then(res => {
                        this.ToastMessage('Successfully added!', 'success');
                        $("#modalDineIn").modal('hide');
                        this.displayTables();
                    })
                    .catch(res => {
                        this.ToastMessage("Error: " + res.message, 'error');
                    });


                });
            },
            removeOrderItem(item) {
                this.showConfirmMessage(() => {
                    this.orderItems.splice(this.orderItems.indexOf(item), 1);
                    this.ToastMessage('Item removed!', 'success');
                });
                
            },
            clearOrderItem() {
                this.showConfirmMessage(() => {
                    this.orderItems = [];
                    this.ToastMessage('Items cleared!', 'success');
                });
            },
            displayTables() {
                this.$Progress.start();
                axios.get('/api/dinein/tables')
                .then(res => {
                    this.tables = res.data.tables;
                    this.products = res.data.products;
                    this.$Progress.finish();

                })
                .catch(error => {
                    this.$Progress.fail();
                });
            },
            showOrder(table) {
                $("#modalOrder").modal('show');
                this.vorderTable = table.table_number;
                axios.get('/api/dinein/orders/' + table.order_id)
                .then(res => this.vorderItems = res.data);
            }
        },
        created() {
            this.displayTables();
        },
        mounted() {
            console.log('Component mounted.')
        }
    }

</script>
