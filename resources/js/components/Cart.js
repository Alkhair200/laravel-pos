import React, { Component } from "react";
import ReactDOM from "react-dom";
import axios from "axios";
import Swal from "sweetalert2";
import { sum } from "lodash";

class Cart extends Component {
    constructor(props) {
        super(props);
        this.state = {
            cart: [],
            products: [],
            customers: [],
            barcode: "",
            search: "",
            customer_id: ""
        };

        this.loadCart = this.loadCart.bind(this);
        this.handleOnChangeBarcode = this.handleOnChangeBarcode.bind(this);
        this.handleScanBarcode = this.handleScanBarcode.bind(this);
        this.handleChangeQty = this.handleChangeQty.bind(this);
        this.handleEmptyCart = this.handleEmptyCart.bind(this);

        this.loadProducts = this.loadProducts.bind(this);
        this.handleChangeSearch = this.handleChangeSearch.bind(this);
        this.handleSeach = this.handleSeach.bind(this);
        this.setCustomerId = this.setCustomerId.bind(this);
        this.handleClickSubmit = this.handleClickSubmit.bind(this)
    }

    componentDidMount() {
        // load user cart
        this.loadCart();
        this.loadProducts();
        this.loadCustomers();
    }

    loadCustomers() {
        axios.get(`/admin/customers`).then(res => {
            const customers = res.data;
            this.setState({ customers });
        });
    }

    loadProducts(search = "") {
        const query = !!search ? `?search=${search}` : "";
        axios.get(`/admin/products${query}`).then(res => {
            const products = res.data.data;
            this.setState({ products });
        });
    }

    handleOnChangeBarcode(event) {
        const barcode = event.target.value;
        console.log(barcode);
        this.setState({ barcode });
    }

    loadCart() {
        axios.get("/admin/cart").then(res => {
            const cart = res.data;
            this.setState({ cart });
        });
    }

    handleScanBarcode(event) {
        event.preventDefault();
        const { barcode } = this.state;
        if (!!barcode) {
            axios
                .post("/admin/cart", { barcode })
                .then(res => {
                    this.loadCart();
                    this.setState({ barcode: "" });
                })
                .catch(err => {
                    Swal.fire("Error!", err.response.data.message, "error");
                });
        }
    }
    handleChangeQty(product_id, qty) {
        const cart = this.state.cart.map(c => {
            if (c.id === product_id) {
                c.pivot.quantity = qty;
            }
            return c;
        });

        this.setState({ cart });

        axios
            .post("/admin/cart/change-qty", { product_id, quantity: qty })
            .then(res => {})
            .catch(err => {
                Swal.fire("Error!", err.response.data.message, "error");
            });
    }

    getTotal(cart) {
        const total = cart.map(c => c.pivot.quantity * c.price);
        return sum(total).toFixed(2);
    }
    handleClickDelete(product_id) {
        axios
            .post("/admin/cart/delete", { product_id, _method: "DELETE" })
            .then(res => {
                const cart = this.state.cart.filter(c => c.id !== product_id);
                this.setState({ cart });
            });
    }
    handleEmptyCart() {
        axios.post("/admin/cart/empty", { _method: "DELETE" }).then(res => {
            this.setState({ cart: [] });
        });
    }
    handleChangeSearch(event) {
        const search = event.target.value;
        this.setState({ search });
    }
    handleSeach(event) {
        if (event.keyCode === 13) {
            this.loadProducts(event.target.value);
        }
    }

    addProductToCart(barcode) {
        let product = this.state.products.find(p => p.barcode === barcode);
        if (!!product) {
            // if product is already in cart
            let cart = this.state.cart.find(c => c.id === product.id);
            if (!!cart) {
                // update quantity
                this.setState({
                    cart: this.state.cart.map(c => {
                        if (c.id === product.id) {
                            c.pivot.quantity = c.pivot.quantity + 1;
                        }
                        return c;
                    })
                });
            } else {
                product = {
                    ...product,
                    pivot: {
                        quantity: 1,
                        product_id: product.id,
                        user_id: 1
                    }
                };

                this.setState({ cart: [...this.state.cart, product] });
            }

            axios
                .post("/admin/cart", { barcode })
                .then(res => {
                    // this.loadCart();
                    console.log(res);
                })
                .catch(err => {
                    Swal.fire("Error!", err.response.data.message, "error");
                });
        }
    }

    setCustomerId(event) {
        this.setState({ customer_id: event.target.value });
    }
    handleClickSubmit() {
        Swal.fire({
            title: 'Received Amount',
            input: 'text',
            inputValue: this.getTotal(this.state.cart),
            showCancelButton: true,
            confirmButtonText: 'Send',
            showLoaderOnConfirm: true,
            preConfirm: (amount) => {
                return axios.post('/admin/orders', { customer_id: this.state.customer_id, amoun t }).then(res => {
                    this.loadCart();
                    return res.data;
                }).catch(err => {
                    Swal.showValidationMessage(err.response.data.message)
                })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.value) {
                //
            }
        })

    }
    render() {
        const { cart, products, customers, barcode } = this.state;
        return ( < <
                iv className = "row " >
                <
                <
                <
                div clas Name = "col-m d -6 col-lg- 4" >
                <
                <
                <
                div classNamrow mb - 2 " >      < <

                andleScanBa r code
            } >


            nt < rol "
        an Bar < code...
        "
        code <
    }
    this.handleOn C hange Barcode
} <
/> <   
"col" >
<
<
{
    seleorm - control " <
    onChangeomerId
    option vCusto m e r < /o p >tion> {
    cust( < { cus.id }
    } <
    value cus.id <
} >
cus.f < irst_name
}
$ { cus.last_name }
` } < /option>
             <
               
            <
/select> <   
            <
/div> <   
            <
/div> < 
            <
div classNamt"  >
            <
<     <
                 <
       <
ame = "tabletabl <e-striped" >
 <
  {
 <
     
        <<
       <
 Name < /th> < 
    th > Quantity < <
/th> <th> <  
  
    
 {
         cart.map(c => (event =>
<
.id } >

e } < /td> <

 <
pe = "text"control-sm   qty"
pivot.quantity }  
             onChange = (){=>

                      event 
                        
                     
                    <
    )      <
                     <
                     <
                    tn-danger bt n -sm"        {
                        
                        this
                        
                    } <
   ) <
                    
                
            } <
       i cla "fas f <a-trash" > < /i> <
            < <
            < <
            d className = "text-right" > { window.APP.currency_symbol } { " " } {
                        (
            <
                   c.pric e  * c. pivot.quantity
            <
            .toFixed(2)       <
                     <
             <
             <
               
            <
/tbody> <   
            <
/table> <   
              
    
    "row" >
 <
className = col" > T <otal: < /div> <
div classNam = "c <ol text-right" > { window.APP.currency_symbol } { this.getTotal(cart) } <
/div> <   
        /div<
> <w" >  
  
sName = "col" >   
    button"
e = "btn btn-dange <r btn-block"
ick = { thishandleEm <ptyCart }
disabled = {cart. <length } >
    Cancel < <
        /buton> < <
        /div <   
            <
    div clasName = "col"  >  
            <
<butto n "
sName = "btn btn-prim a ry btn-block"
bled = {!cart.length }  
ick = { this.handleC l i ckSubmit } > 
it <    
/button> < <
    /div> < <
    /div> <    {
    /div> < <
    div className = col onClick = {
    < 
div className = "mb-2"  >   
<   >
    input type = "te<t"
className = "form-cotrol"    
                      
                    <
placeholder = "Searc P r o duct.. . "  <
    onChange = { thi.handleChangeSearch }
         onKeyDo))
wn =        } <
    /> < <
        /div < <
            iv className = "order-product" > {
                products.map(p => ( <
                    div onClick = {
                        () => this.addProductToCart(p.barcode) }
                    key = { p.id }
                    className = "item" >
                    <
                    img src = { p.image_url }
                     a lt = " "  / >
                     h5 > { p.name } < /h5> <
                    /div>
                ))
            } <
            /div> <
            /div> <
            /div>
        );
    }
}

export default Cart;

if (document.getElementById("cart")) {
    ReactDOM.render( < Cart / > , document.getElementById("cart"));
}