<section class="filter">
    <form action="#filter" class="form-inline">
        <div class="form-group"><label for="currency-select"
                                       class="sr-only">Currency</label><select name="currency"
                                                                               id="currency-select">
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
            </select></div>
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
        <td>{{ offer.getName() }}</td>
        <td>{{ offer.getType() }}</td>
        <td>{{ offer.getCurrency() }}</td>
        <td>{{ offer.getAmount() }}</td>
        <td>{{ offer.getRate() }}</td>
        <td>{{ offer.getEndDate() }}</td>
    </tr>
    {% endfor %}
    </tbody>
</table>
{% else %}
    <div class="alert alert-warning">No offers found</div>
{% endif %}