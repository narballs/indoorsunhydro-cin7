 <tr>
                    <td>{{ $count }}</td>
                    <td>{{$product->name}}</td>
                    <td>
                        {{$product->status}}
                    </td>
                    <td>{{$product->code}}</td>
                    <td>${{$product->retail_price}}</td>
                    <td>
                        <a href="{{ url('admin/products/'.$product->id) }}" class="view" title="" data-toggle="tooltip"
                            data-original-title="View"><i class="fas fa-eye"></i></a>
                        <a href="#" class="edit" title="" data-toggle="tooltip" data-original-title="Edit"><i
                                class="fas fa-pen"></i></a>
                        <a href="#" class="delete" title="" data-toggle="tooltip" data-original-title="Delete"><i
                                class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>