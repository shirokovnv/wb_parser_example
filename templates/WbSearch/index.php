<?php

echo $this->Form->create($wbProductsSearch, ['action' => '/wbSearch', 'method' => 'POST']);
echo $this->Form->control('query', ['label' => 'Фраза', 'value' => htmlentities( (string) $userQuery ) ]);
echo $this->Form->button('Поиск', ['style' => 'color: white; border-color: #5741e9; background-color: #5741e9;']);
echo $this->Form->end();

?>
<?php if (isset($products)) {?>
    <?php if (!empty($products)) {?>
        <ul class="pagination">
            <?php
            for ($page = 1; $page <= 10; $page++) { ?>
                <li>
                    <?php if ($page === $currentPage) { ?><span><?php echo $page; ?></span>
                    <?php } else { ?>
                        <a href="/wbSearch?page=<?php echo $page; ?>&query=<?php echo htmlentities( $userQuery ); ?>">
                            <?php echo $page; ?>
                        </a>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>

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
        <h4>Пустой список.</h4>
    <?php } ?>
<?php } ?>
