@extends('layouts.app')
@section('content')
<div class="m-4">
    <nav class="nav nav-tabs">
        <a href="#" class="nav-item nav-link active" data-bs-toggle="tab" data-bs-target="#nav-credit-card" >
            Cartão de crédito ou débito
        </a>
        <a href="#" class="nav-item nav-link" data-bs-toggle="tab" data-bs-target="#nav-ticket" >
            Boleto Bancário
        </a>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-credit-card" role="tabpanel" aria-labelledby="nav-credit-card-tab">
            <input type="hidden" id="mercado-pago-public-key" value="{{ env('MERCADO_PAGO_PUBLIC_KEY') }}">
            <section class="payment-form card mt-4">
                <div class="payment card-body">
                    <div class="block-heading">
                        <h2>Pagamento com cartão de crédito/débito</h2>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{asset('storage/foto-album.jpg')}}" alt="" class="img-fluid">
                            <h4 class="mt-4">Valor: <strong class="text-success">R$ 45,00</strong></h4>
                            <span id="product-description">Tobias Sammet - Avantasia - The mistery of time</span>
                        </div>
                        <div class="col-md-8">
                            <div class="form-payment">
                                <div class="payment-details">
                                    <form id="form-checkout">
                                        <h3 class="title">Detalhes do comprador</h3>
                                        <div class="row">
                                            <div class="form-group col mb-2">
                                                <input id="card-email" name="cardholderEmail" type="email" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-5">
                                                <select id="identification-type" name="identificationType" class="form-control"></select>
                                            </div>
                                            <div class="form-group col-sm-7">
                                                <input id="identification-number" name="docNumber" type="text" class="form-control"/>
                                            </div>
                                        </div>
                                        <br>
                                        <h3 class="title">Detalhes do cartão</h3>
                                        <div class="row">
                                            <div class="form-group col-sm-8 mb-2">
                                                <input id="card-holder-name" name="cardholderName" type="text" class="form-control"/>
                                            </div>
                                            <div class="form-group col-sm-4 mb-2">
                                                <div class="input-group expiration-date">
                                                    <div id="expiration-date" class="form-control"></div>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-8 mb-2">
                                                <div id="card-number" class="form-control"></div>
                                            </div>
                                            <div class="form-group col-sm-4 mb-2">
                                                <div id="security-code" class="form-control h-40"></div>
                                            </div>
                                            <div id="issuerInput" class="form-group col-sm-12 hidden mb-2">
                                                <select id="checkout-issuer" name="issuer" class="form-control"></select>
                                            </div>
                                            <div class="form-group col-sm-12">
                                                <select id="checkout-installments" name="installments" type="text" class="form-control"></select>
                                            </div>
                                            <div class="form-group col-sm-12">
                                                <input type="hidden" id="amount" value="45.00" />
                                                <input type="hidden" id="description" />
                                                <div id="validation-error-messages" class="mb-4">
                                                </div>
                                                <button id="button-pay" type="submit" class="btn btn-primary btn-block mb-4">Pagar</button>
                                                <p id="loading-message">Carregando, por favor aguarde...</p>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="result" class="items product info product-details card-body">
                    <div class="row justify-content-md-center">
                        <div class="col-md-4 product-detail">
                            <div class="product-info">
                                <div id="fail-response">
                                    <img src="{{asset('storage/fail.png')}}" class="img-fluid">
                                    <p class="text-center font-weight-bold">Ops! Algo deu errado!</p>
                                    <p id="error-message" class="text-center"></p>
                                </div>

                                <div id="success-response">
                                    <p><b>ID: </b><span id="payment-id"></span></p>
                                    <p><b>Status: </b><span id="payment-status"></span></p>
                                    <p><b>Detalhes: </b><span id="payment-detail"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="tab-pane fade" id="nav-ticket" role="tabpanel" aria-labelledby="nav-ticket-tab">
            <section class="payment-form-ticket card mt-4">
                <div class="payment-ticket card-body">
                    <div class="block-heading">
                        <h2>Pagamento com boleto bancário</h2>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{asset('storage/foto-album.jpg')}}" alt="" class="img-fluid">
                            <h4 class="mt-4">Valor: <strong class="text-success">R$ 100,00</strong></h4>
                            <span id="product-description-ticket">Tobias Sammet - Avantasia - The mistery of time</span>
                        </div>
                        <div class="col-md-8">
                            <div class="form-payment">
                                <div class="payment-details">
                                    <form id="form-checkout-ticket" action="/processar-pagamento-boleto" method="post">
                                        @csrf
                                        <h3 class="title">Dados pessoais</h3>
                                        <div class="row">
                                            <div class="form-group col-sm-8 mb-2">
                                                <input id="ticket-first-name" name="payerFirstName" type="text" placeholder="Nome" class="form-control"/>
                                            </div>
                                            <div class="form-group col-sm-8 mb-2">
                                                <input id="ticket-last-name" name="payerLastName" type="text" placeholder="Sobrenome" class="form-control"/>
                                            </div>
                                            <div class="form-group col-sm-8 mb-2">
                                                <input id="ticket-identification-email" name="email" type="email" placeholder="E-mail" class="form-control" />
                                            </div>
                                            <div class="form-group col-sm-8 mb-2">
                                                <select id="ticket-identification-type" name="identificationType" class="form-control"><option value="">Tipo de documento</option></select>
                                            </div>
                                            <div class="form-group col-sm-8 mb-2">
                                                <input id="ticket-identification-number" name="identificationNumber" type="text" placeholder="Número do documento" class="form-control" />
                                            </div>
                                        </div>
                                        <h3 class="title">Endereço</h3>
                                        <div class="row">
                                            <div class="form-group col-sm-8 mb-2">
                                                <input id="ticket-zip-code" name="payerZipCode" type="text" placeholder="CEP" class="form-control"/>
                                            </div>
                                            <div class="form-group col-sm-8 mb-2">
                                                <input id="ticket-street-name" name="payerStreetName" type="text" placeholder="Rua" class="form-control"/>
                                            </div>
                                            <div class="form-group col-sm-8 mb-2">
                                                <input id="ticket-street-number" name="payerStreetNumber" type="number" placeholder="Número" class="form-control" />
                                            </div>
                                            <div class="form-group col-sm-8 mb-2">
                                                <input id="ticket-neighborhood" name="payerNeightborhood" type="text" placeholder="Bairro" class="form-control" />
                                            </div>
                                            <div class="form-group col-sm-8 mb-2">
                                                <input id="ticket-city" name="payerCity" type="text" placeholder="Cidade" class="form-control" />
                                            </div>
                                            <div class="form-group col-sm-8 mb-2">
                                                <input id="ticket-federal-unit" name="payerFederalUnit" type="text" placeholder="Estado" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-12">
                                                <input type="hidden" id="amount-ticket" name="amountTicket" value="100" />
                                                <input type="hidden" id="description-ticket" name="descriptionTicket" value="Tobias Sammet - Avantasia - The mistery of time" />
                                                <div id="validation-error-messages-ticket" class="mb-4">
                                                </div>
                                                <button id="button-pay-ticket" type="submit" class="btn btn-primary btn-block mb-4">Pagar</button>
                                                <p id="loading-message">Carregando, por favor aguarde...</p>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="fail-response-ticket">
                    <img src="{{asset('storage/fail.png')}}" class="img-fluid">
                    <p class="text-center font-weight-bold">Ops! Algo deu errado!</p>
                    <p id="error-message-ticket" class="text-center"></p>
                </div>
                <div id="success-response-ticket">
                </div>
            </section>
        </div>
      </div>
</div>
@if (\Session::has('success'))
    <div class="alert alert-success">
        {!! \Session::get('success') !!}
    </div>
@endif
@endsection
@section('script')
<script>
    const mp = new MercadoPago('{{ env('MERCADO_PAGO_PUBLIC_KEY') }}');

    /*
    Cartão de crédito
    */
    function loadCardForm() {
        const productCost = document.getElementById('amount').value;
        const productDescription = document.getElementById('product-description').innerText;
        const payButton = document.getElementById("button-pay");
        const validationErrorMessages= document.getElementById('validation-error-messages');

        const form = {
            id: "form-checkout",
            cardholderName: {
                id: "card-holder-name",
                placeholder: "Nome do titular",
            },
            cardholderEmail: {
                id: "card-email",
                placeholder: "E-mail",
            },
            cardNumber: {
                id: "card-number",
                placeholder: "Número do cartão",
                style: {
                    fontSize: "0.9rem"
                },
            },
            expirationDate: {
                id: "expiration-date",
                placeholder: "MM/YYYY",
                style: {
                    fontSize: "0.9rem"
                },
            },
            securityCode: {
                id: "security-code",
                placeholder: "Código de segurança",
                style: {
                    fontSize: "0.9rem"
                },
            },
            installments: {
                id: "checkout-installments",
                placeholder: "Parcelamento",
            },
            identificationType: {
                id: "identification-type",
            },
            identificationNumber: {
                id: "identification-number",
                placeholder: "Número de identificação",
            },
            issuer: {
                id: "checkout-issuer",
                placeholder: "Emissor",
            },
        };

        const cardForm = mp.cardForm({
            amount: productCost,
            iframe: true,
            form,
            callbacks: {
                onFormMounted: error => {
                    if (error)
                        return console.warn("Form Mounted handling error: ", error);
                    console.log("Form mounted");
                },
                onSubmit: event => {
                    event.preventDefault();
                    document.getElementById("loading-message").style.display = "block";

                    const {
                        paymentMethodId,
                        issuerId,
                        cardholderEmail: email,
                        amount,
                        token,
                        installments,
                        identificationNumber,
                        identificationType,
                    } = cardForm.getCardFormData();

                    fetch("/processar-pagamento-cartao", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            'Accept': 'application/json',
                            "X-CSRF-Token": $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify({
                            token,
                            issuerId,
                            paymentMethodId,
                            transactionAmount: Number(amount),
                            installments: Number(installments),
                            description: productDescription,
                            payer: {
                                email,
                                identification: {
                                    type: identificationType,
                                    number: identificationNumber,
                                },
                            },
                        }),
                    })
                    .then(response => {
                        return response.json();
                    })
                    .then(result => {
                        if(!result.hasOwnProperty("error_message")) {
                            document.getElementById("success-response").style.display = "block";
                            document.getElementById("payment-id").innerText = result.id;
                            document.getElementById("payment-status").innerText = result.status;
                            document.getElementById("payment-detail").innerText = result.detail;
                        } else {
                            document.getElementById("error-message").textContent = result.error_message;
                            document.getElementById("fail-response").style.display = "block";
                        }
                        
                        $('.payment').fadeOut(500);
                        setTimeout(() => { $('#result').show(500).fadeIn(); }, 500);
                    })
                    .catch(error => {
                        alert("Erro inesperado\n"+JSON.stringify(error));
                    });
                },
                onFetching: (resource) => {
                    console.log("Fetching resource: ", resource);
                    payButton.setAttribute('disabled', true);
                    return () => {
                        payButton.removeAttribute("disabled");
                    };
                },
                onCardTokenReceived: (errorData, token) => {
                    if (errorData && errorData.error.fieldErrors.length !== 0) {
                        errorData.error.fieldErrors.forEach(errorMessage => {
                            alert(errorMessage);
                        });
                    }

                    return token;
                },
                onValidityChange: (error, field) => {
                    const input = document.getElementById(form[field].id);
                    removeFieldErrorMessages(input, validationErrorMessages);
                    addFieldErrorMessages(input, validationErrorMessages, error);
                    enableOrDisablePayButton(validationErrorMessages, payButton);
                }
            },
        });
    };

    function removeFieldErrorMessages(input, validationErrorMessages) {
        Array.from(validationErrorMessages.children).forEach(child => {
            const shouldRemoveChild = child.id.includes(input.id);
            if (shouldRemoveChild) {
                validationErrorMessages.removeChild(child);
            }
        });
    }

    function addFieldErrorMessages(input, validationErrorMessages, error) {
        if (error) {
            input.classList.add('validation-error');
            error.forEach((e, index) => {
                const p = document.createElement('p');
                p.id = `${input.id}-${index}`;
                p.innerText = e.message;
                validationErrorMessages.appendChild(p);
            });
        } else {
            input.classList.remove('validation-error');
        }
    }

    function enableOrDisablePayButton(validationErrorMessages, payButton) {
        if (validationErrorMessages.children.length > 0) {
            payButton.setAttribute('disabled', true);
        } else {
            payButton.removeAttribute('disabled');
        }
    } 
    setTimeout(() => {
        loadCardForm();
        $('.payment').show(500).fadeIn();
    }, 500);

    /*
    Boleto
    */
    function loadTicketForm() {
        (async function getIdentificationTypes() {
            try {
                const identificationTypes = await mp.getIdentificationTypes();
                const identificationTypeElement = document.getElementById('ticket-identification-type');

                createSelectOptions(identificationTypeElement, identificationTypes);
            } catch (e) {
                return console.error('Error getting identificationTypes: ', e);
            }
            })();

            function createSelectOptions(elem, options, labelsAndKeys = { label: "name", value: "id" }) {
            const { label, value } = labelsAndKeys;

            elem.options.length = 0;

            const tempOptions = document.createDocumentFragment();

            options.forEach(option => {
                const optValue = option[value];
                const optLabel = option[label];

                const opt = document.createElement('option');
                opt.value = optValue;
                opt.textContent = optLabel;

                tempOptions.appendChild(opt);
            });

            elem.appendChild(tempOptions);
        }
    };
    setTimeout(() => {
        loadTicketForm();
    }, 500);
</script>
@endsection