import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class App extends Component {

    constructor(props) {
        super(props);

        // Default State Attributes
        this.state = {
            mealName: '',
            latitude: '',
            longitude: '',
            restaurants: [],
            errors: []
        };

        // Binding
        this.handleChangeMealName = this.handleChangeMealName.bind(this);
        this.handleChangeLatitude = this.handleChangeLatitude.bind(this);
        this.handleChangeLongitude = this.handleChangeLongitude.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.renderRestaurants = this.renderRestaurants.bind(this);
        this.renderErrors = this.renderErrors.bind(this);
    }

    // Handle Meal Name Change
    handleChangeMealName(e) {
        this.setState({
            mealName: e.target.value
        });
        console.log('onChange', this.state.mealName);
    }

    // Handle Latitude Change
    handleChangeLatitude(e) {
        this.setState({
            latitude: e.target.value
        });
        console.log('onChange', this.state.latitude);
    }

    // Handle Longitude Change
    handleChangeLongitude(e) {
        this.setState({
            longitude: e.target.value
        });
        console.log('onChange', this.state.longitude);
    }

    // Handle Form Submit
    handleSubmit(e) {
        e.preventDefault();
        axios
            .post('http://meal.local/api/recommend-meal', {
                mealName: this.state.mealName,
                latitude: this.state.latitude,
                longitude: this.state.longitude
            })
            .then(response => {
                console.log('from handle submit', response.data);

                this.setState({
                    restaurants: response.data.data,
                    errors : []
                });
            })
            .catch((error) => {
                this.setState({
                    errors: error.response.data.errors,
                    restaurants : []
                });
            });
    }

    renderRestaurants() {
        return this.state.restaurants.map((restaurant, index) => (
            <div key={restaurant.id} class="alert alert-info" role="alert">
                {restaurant.name}
            </div>
        ));
    }

    renderErrors() {
        
        return this.state.errors.map((error, index) => (
            <div key={index} class="alert alert-danger" role="alert">
                {error}
            </div>
        ));
    }

    // Rendering Component HTML
    render() {
        return (
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-md-8">
                        <div className="card">
                            <div className="card-header">Meals Recommendation</div>
                            <div className="card-body">
                                <form onSubmit={this.handleSubmit} className="form-inline">
                                
                                    <input type="text" onChange={this.handleChangeMealName} className="form-control mb-2 mr-sm-2" id="mealName" placeholder="Meal Name" />
                                    <input type="text" onChange={this.handleChangeLatitude} className="form-control mb-2 mr-sm-2" id="latitude" placeholder="Latitude" />
                                    <input type="text" onChange={this.handleChangeLongitude} className="form-control mb-2 mr-sm-2" id="longitude" placeholder="Longitude" />

                                    <button type="submit" className="btn btn-primary mb-2">Find</button>
                                </form>
                                <hr />
                                {this.renderErrors()}
                                {this.renderRestaurants()}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}