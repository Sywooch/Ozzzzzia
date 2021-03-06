var SearchInput = require('./search-input.jsx').default;

export default class SearchRegionList extends React.Component {
    constructor(props) {

        super(props);
        this.state = {
            cities : props.cities,
        };
        
    }
    
    /**
     * Обновить список городов
     */
    updateCitiesList(list) {
        
        console.log(list)
        this.setState({cities: list});
    }
    
    changeLocation(url){
        Navigation.changeLocation(url);
    }
  
    render() {
        return ( 
            <div>
                <SearchInput addTodoItem={this.updateCitiesList.bind(this)} 
                            url={this.props.url}
                            list="{this.state.cities}"/>
                <div className="selectboxmenu-items js-scroll">
                {console.log(this.state.cities)}
                    {this.state.cities.map(function(city){
                        return (
                            <span className="a-like" onClick={(e) => this.changeLocation(city.url)}>
                    {console.log(city)}
                                <span><b>{city.name}</b> - {city.region.name}</span>
                            </span>
                        )
                    }.bind(this))}
                </div>
            </div>   
        )
    }
}