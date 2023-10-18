<?php

echo $this->Form->create($wbProductsSearch, ['action' => '/wbSearch']);
echo $this->Form->control('query', ['label' => 'Фраза']);
echo $this->Form->button('Поиск', ['style' => 'color: white; border-color: #5741e9; background-color: #5741e9;']);
echo $this->Form->end();

?>
<?php if (isset($products)) {?>
    <?php if (!empty($products)) {?>
        <table>
            <thead>
                <th>Позиция</th>
                <th>Название</th>
                <th>Бренд</th>
            </thead>
            <tbody>
                <? foreach ($products as $product) {?>
                    <tr>
                        <td><?php echo htmlentities( $product->getPosition() ); ?></td>
                        <td><?php echo htmlentities( $product->getName() ); ?></td>
                        <td><?php echo htmlentities( $product->getBrand() ); ?></td>
                    </tr>
                <? } ?>
            </tbody>
        </table>
    <?php } else {?>
        <h3>Пустой список.</h3>
    <?php } ?>
<?php } ?>
