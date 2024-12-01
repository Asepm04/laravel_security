<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<tr?>
    @foreach($contacts as  $contact)
    <td>{{$contact->name}}
        @can("update",$contact)
        Edit
        @endcan

        @cannot("update",$contact)
         No Edit
        @endcannot
         
    </td>
    <td>
    @can("delete",$contact)
        Delete
        @endcan

        @cannot("delete",$contact)
         No Delete
        @endcannot
    </td>
    <td></td>
    @endforeach
</tr>
    
</body>
</html>