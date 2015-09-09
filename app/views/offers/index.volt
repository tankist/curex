<section class="filter">
    <form action="#filter" class="form-inline">
        <div class="form-group"><label for="currency-select">Currency</label><select name="currency"
                                                                               id="currency-select">
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
            </select></div>
        <a class="btn btn-primary" href="{{url(['for': 'newOffer'])}}" role="button">Add offer</a>
    </form>
</section>
{% if offers.count() > 0 %}
<table class="table table-striped">
    <thead>
    <tr>
        <td>Name</td>
        <td>Operation</td>
        <td>Currency</td>
        <td>Amount</td>
        <td>Rate</td>
        <td>End Date</td>
    </tr>
    </thead>
    <tbody>
    {% for offer in offers %}
    <tr>
        <td>{{ offer.getUser().getFullName() }}</td>
        <td>{{ offer.getOfferType() | capitalize }}</td>
        <td>{{ offer.getCurrency().getTitle() }}</td>
        <td>{{ offer.getAmount() }}</td>
        <td>{{ offer.getRate() }}</td>
        <td>{{ offer.getEndDate() | default("Unlimited") }}</td>
    </tr>
    {% endfor %}
    </tbody>
</table>
{% else %}
    <div class="alert alert-warning">No offers found</div>
{% endif %}