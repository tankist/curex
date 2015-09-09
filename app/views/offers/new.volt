<div class="row">
    <div class="col-lg-offset-3 col-lg-6">
        <form action="{{ url(['for': 'saveOffer']) }}" class="form-horizontal" method="post">

            <input type="hidden" name="id" id="id">

            <div class="form-group">
                <div class="col-md-2 col-md-offset-2"><label class="radio" for="offer_type_buy"><input type="radio"
                                                                                                        name="offer_type"
                                                                                                        id="offer_type_buy"
                                                                                                        value="buy"
                                                                                                        data-toggle="radio"
                                                                                                        checked>Buy</label>
                </div>
                <div class="col-md-2"><label class="radio" for="offer_type_sell"><input type="radio"
                                                                                                         name="offer_type"
                                                                                                         id="offer_type_sell"
                                                                                                         value="sell"
                                                                                                         data-toggle="radio">Sell</label>
                </div>
            </div>

            <div class="form-group"><label for="currency" class="col-sm-2 control-label">Currency</label>

                <div class="col-sm-10">{{
                    form
                        .get('currency_id')
                        .setAttribute('class', 'form-control select select-primary mbl')
                        .setAttribute('data-toggle', 'select')
                    }}</div>
            </div>
            <div class="form-group"><label for="amount" class="col-sm-2 control-label">Amount</label>

                <div class="col-sm-10">{{ form.get('amount').setAttribute('class', 'form-control') }}</div>
            </div>
            <div class="form-group"><label for="rate" class="col-sm-2 control-label">Rate</label>

                <div class="col-sm-10">{{ form.get('rate').setAttribute('class', 'form-control') }}</div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-6 col-sm-2">
                    <button type="submit" class="btn btn-primary" style="width: 100%">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>