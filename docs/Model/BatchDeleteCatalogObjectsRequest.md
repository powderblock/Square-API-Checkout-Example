# BatchDeleteCatalogObjectsRequest

### Description



## Properties
Name | Getter | Setter | Type | Description | Notes
------------ | ------------- | ------------- | ------------- | ------------- | -------------
**object_ids** | getObjectIds() | setObjectIds($value) | **string[]** | The IDs of the &#x60;CatalogObject&#x60;s to be deleted. When an object is deleted, other objects in the graph that depend on that object will be deleted as well (for example, deleting a &#x60;CatalogItem](#type-catalogitem) will delete its [CatalogItemVariation&#x60;. | [optional] 

Note: All properties are protected and only accessed via getters and setters.

[[Back to Model list]](../../README.md#documentation-for-models) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to README]](../../README.md)

